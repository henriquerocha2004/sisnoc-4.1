<?php

namespace App\Http\Controllers;

use App\Models\Called;
use App\Models\Establishment;
use App\Models\Links;
use App\Models\Notes;
use App\Models\ProblemCause;
use App\Models\RegionalManager;
use App\Models\SubCaller;
use App\Models\TechnicalManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MigrationController extends Controller
{

    public function RegionalManager(){

        $gerenteRegionais = DB::connection('sisnoc_prod')->table('tb_ger_reg')->get();

        foreach($gerenteRegionais as $gerente){
           $gerenteRegional = new RegionalManager();
           $gerenteRegional->id = $gerente->gr_cod + 1;
           $gerenteRegional->name = $gerente->gr_nome;
           $gerenteRegional->email = $gerente->gr_email;
           $gerenteRegional->contact = $gerente->gr_corp;
           $gerenteRegional->status = 'active';

           $gerenteRegional->save();
        }

        echo "FIM DA EXECUÇÂO";
    }

    public function TecnicalManager(){
        $tecnicos = DB::connection('sisnoc_prod')->table('tb_resp_tec')->get();

        foreach($tecnicos as $tecnico){

            $respTec = new TechnicalManager();
            $respTec->id = $tecnico->resp_cod + 1;
            $respTec->name = $tecnico->resp_nome;
            $respTec->email = $tecnico->resp_email;
            $respTec->contact = $tecnico->resp_corp;
            $respTec->status = 'active';
            $respTec->save();
        }

        echo "FIM DA EXECUÇÂO";
    }


    public function establishment(){

        DB::beginTransaction();
        try {

            $lojas = DB::connection('sisnoc_prod')->table('tb_lojas')->
        whereIn('gr_cod', [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,27,28,29,30,31,32,47,48])
        ->whereIn('tr_cod', [1,2,3,4,5,6,7,8,9])->get();

        foreach($lojas as $loja){

           $estabelecimento = new Establishment();
           $estabelecimento->establishment_code = $loja->lj_num;
           $estabelecimento->establishment_status = ($loja->lj_sit == 'Aberta' ? 'open' : 'close');
           $estabelecimento->document_establishment = $loja->lj_cnpj;
           $estabelecimento->document_establishment_alternate = $loja->lj_ie;
           $estabelecimento->address = $loja->lj_end;
           $estabelecimento->neighborhood = $loja->lj_bairro;
           $estabelecimento->city = $loja->lj_cidade;
           $estabelecimento->state = $loja->lj_uf;
           $estabelecimento->opening_hours = $loja->hr_cod;
           $estabelecimento->location = $loja->lj_tipo;
           $estabelecimento->manager_name = $loja->lj_ger;
           $estabelecimento->manager_contact = $loja->lj_tel_ger;
           $estabelecimento->regional_manager_code = $loja->gr_cod + 1;
           $estabelecimento->technician_code = $loja->tr_cod;
           $estabelecimento->phone_establishment = $loja->lj_tel_fix;
           $estabelecimento->branch_establishment = $loja->lj_tel_ram;
           $estabelecimento->id_user = 1;
           $estabelecimento->save();

        }

        DB::commit();

        } catch (\Exception $e) {
          DB::rollback();
          dd($e->getMessage());
        }

        echo "Fim da Execução";
    }


    public function links(){

        DB::beginTransaction();

        try {
            $circuitos = DB::connection('sisnoc_prod')->table('tb_circuitos')->get();

            foreach($circuitos as $circuito){

                $esta = Establishment::where(['establishment_code' => $circuito->cir_loja])->first();

                if($esta != null){
                    $link = new Links();
                    $link->establishment_id = $esta->id;
                    $link->type_link = $circuito->cir_link;
                    $link->bandwidth = $circuito->cir_band;
                    $link->link_identification = $circuito->cir_desig;
                    $link->telecommunications_company = $circuito->cir_oper;
                    $link->monitoring_ip = $circuito->cir_ip_link;
                    $link->installed_router_model = $circuito->cir_model_router;
                    $link->serial_router = $circuito->cir_serial_chassi;
                    $link->local_ip_router = $circuito->cir_ip_lan_router;
                    $link->status = ($esta->establishment_status == 'open' ? 'active' : 'inactive');

                    $link->save();
                }

            }

            DB::commit();


        } catch (\Exception $e) {
          DB::rollback();
          dd($e->getMessage());
        }
        echo "Fim da Execução";
    }


    public function lojas(){
        set_time_limit(3600);

        DB::beginTransaction();

        try {

          $ocorrencias = DB::connection('sisnoc_prod')->table('tb_ocorrencias')->get();

          foreach ($ocorrencias as $ocorrencia)
          {

                  $loja = Establishment::where(['establishment_code' => $ocorrencia->o_loja])->first();
                  $causeProblem  = ProblemCause::where('description_cause', 'like', "%".$ocorrencia->o_causa_prob."%")->first();
                  $link = Links::where(['type_link' => $ocorrencia->o_link])->first();

                  if(!empty($loja->id) && !empty($link))
                  {
                    $Notas = DB::connection('sisnoc_prod')->table('tb_ch_notas')->where('o_cod', $ocorrencia->o_cod)->get();

                   //Migrando da tabela antiga para a nova
                   $called = new Called();
                   $called->caller_number = date('Ymd', strtotime($ocorrencia->o_hr_ch)). rand(50, 300);
                   $called->id_establishment = $loja->id;
                   $called->id_link = $link->id;
                   $called->status = $ocorrencia->o_sit_ch;
                   $called->next_action = $ocorrencia->o_nece ?? $ocorrencia->o_sit_ch;
                   $called->id_user_open = 1;
                   $called->hr_down = $ocorrencia->o_hr_dw;

                   if($ocorrencia->o_sit_ch == 1){

                        $called->id_problem_cause = (!empty($causeProblem->id) ? $causeProblem->id : 16);
                        $called->id_user_close = 1;
                        $called->hr_up = $ocorrencia->o_hr_up;
                        $called->downtime = $ocorrencia->o_time_ind;
                        $called->work_downtime = $ocorrencia->o_time_work;
                   }

                   $called->save();


                      //Migrando para a tabela subocorrencias

                      if(!empty($ocorrencia->o_prot_op))
                      {
                        $subCaller = new SubCaller();
                        $subCaller->id_caller = $called->id;
                        $subCaller->status = 'close';
                        $subCaller->id_user = 1;
                        $subCaller->call_telecommunications_company_number = $ocorrencia->o_prot_op;
                        $subCaller->deadline = $ocorrencia->o_prazo;
                        $subCaller->hr_open_call_telecommunications_company = $ocorrencia->o_hr_ch_op;
                        $subCaller->type = 2;
                        $subCaller->status_establishment = ($ocorrencia->o_status == 'Loja Offline' ? 1 : 2);

                        $subCaller->save();



                         //Marcando tipo de problema e acão tomada
                        DB::connection('mysql')->table('action_taken_called')->insert([
                            'id_caller' => $subCaller->id,
                            'id_action_taken' => 6
                        ]);
                        DB::connection('mysql')->table('problem_type_called')->insert([
                            'id_called' => $subCaller->id,
                            'id_problem_type' => 1
                        ]);

                           //Notas da ocorrencia
                          if(count($Notas) > 0)
                          {
                              foreach ($Notas as $nota)
                              {
                                $note = new Notes();
                                $note->id_sub_caller = $subCaller->id;
                                $note->content = $nota->ch_nota;
                                $note->save();
                              }
                          }
                      }
                      if(!empty($ocorrencia->o_sisman))
                      {
                        $subCaller = new SubCaller();
                        $subCaller->id_caller = $called->id;
                        $subCaller->status = 'close';
                        $subCaller->id_user = 1;
                        $subCaller->sisman = $ocorrencia->o_sisman;
                        $subCaller->type = 4;
                        $subCaller->status_establishment = ($ocorrencia->o_status == 'Loja Offline' ? 1 : 2);

                        $subCaller->save();

                         //Marcando tipo de problema e acão tomada
                         DB::connection('mysql')->table('action_taken_called')->insert([
                            'id_caller' => $subCaller->id,
                            'id_action_taken' => 6
                        ]);
                        DB::connection('mysql')->table('problem_type_called')->insert([
                            'id_called' => $subCaller->id,
                            'id_problem_type' => 1
                        ]);
                           //Notas da ocorrencia
                          if(count($Notas) > 0)
                          {
                              foreach ($Notas as $nota)
                              {
                                $note = new Notes();
                                $note->id_sub_caller = $subCaller->id;
                                $note->content = $nota->ch_nota;
                                $note->save();
                              }
                          }
                      }
                      if(!empty($ocorrencia->o_otrs))
                      {
                        $subCaller = new SubCaller();
                        $subCaller->id_caller = $called->id;
                        $subCaller->status = 'close';
                        $subCaller->id_user = 1;
                        $subCaller->otrs = $ocorrencia->o_otrs;
                        $subCaller->type = 3;
                        $subCaller->status_establishment = ($ocorrencia->o_status == 'Loja Offline' ? 1 : 2);

                        $subCaller->save();



                         //Marcando tipo de problema e acão tomada
                         DB::connection('mysql')->table('action_taken_called')->insert([
                            'id_caller' => $subCaller->id,
                            'id_action_taken' => 6
                        ]);
                        DB::connection('mysql')->table('problem_type_called')->insert([
                            'id_called' => $subCaller->id,
                            'id_problem_type' => 1
                        ]);

                           //Notas da ocorrencia
                          if(count($Notas) > 0)
                          {
                              foreach ($Notas as $nota)
                              {
                                $note = new Notes();
                                $note->id_sub_caller = $subCaller->id;
                                $note->content = $nota->ch_nota;
                                $note->save();
                              }
                          }
                      }
                      if($ocorrencia->o_nece == 5)
                      {
                        $subCaller = new SubCaller();
                        $subCaller->id_caller = $called->id;
                        $subCaller->status = 'close';
                        $subCaller->id_user = 1;
                        $subCaller->call_telecommunications_company_number = $ocorrencia->o_prot_op;
                        $subCaller->deadline = $ocorrencia->o_prazo;
                        $subCaller->hr_open_call_telecommunications_company = $ocorrencia->o_hr_ch_op;
                        $subCaller->type = 5;
                        $subCaller->status_establishment = 1;

                        $subCaller->save();

                         //Marcando tipo de problema e acão tomada
                         //Marcando tipo de problema e acão tomada
                         DB::connection('mysql')->table('action_taken_called')->insert([
                            'id_caller' => $subCaller->id,
                            'id_action_taken' => 6
                        ]);
                        DB::connection('mysql')->table('problem_type_called')->insert([
                            'id_called' => $subCaller->id,
                            'id_problem_type' => 1
                        ]);
                           //Notas da ocorrencia
                          if(count($Notas) > 0)
                          {
                              foreach ($Notas as $nota)
                              {
                                $note = new Notes();
                                $note->id_sub_caller = $subCaller->id;
                                $note->content = $nota->ch_nota;
                                $note->save();
                              }
                          }
                      }
                  }

              }

          DB::commit();

        }catch (\Exception $e) {
            DB::rollback();
            dd($e->getMessage(), $e->getLine(), $e->getFile(), $e->getTrace());;
            //throw $th;
        }
    }

}
