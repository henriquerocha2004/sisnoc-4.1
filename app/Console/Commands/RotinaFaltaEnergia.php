<?php

namespace App\Console\Commands;

use App\Models\Called;
use App\Utils\DateUtils;
use App\Utils\NetWork;
use Illuminate\Console\Command;

class RotinaFaltaEnergia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'FaltaDeEnergia:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rotina para fechar chamado de falta de energia';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
       $chamados = Called::with(['subCallers', 'link', 'establishment'])->where(['status' => 5])->get();

       foreach($chamados as $called){

           $link = $called->link()->first();

           $testePing = json_decode( NetWork::testePing($link->monitoring_ip, $link->type_link));

           if($testePing->retorno == true){
                $called->hr_up = date('Y-m-d H:i:s');
                $called->id_problem_cause = 35;
                $called->id_user_close = 3;
                $called->status = 1;
                $called->downtime = DateUtils::calcDowntime($called->hr_down, date('Y-m-d H:i:s'));
                $called->work_downtime = DateUtils::calcWorkDowntime($called->hr_up, $called->hr_down, $called->downtime);
                $called->save();

                $subCaller = $called->subCallers()->first();

                $subCaller->status = 'closed';
                $subCaller->id_user_close = 3;
                $subCaller->save();

                $establishment = $called->establishment()->first();
                $establishment->energy_fault = 0;
                $establishment->save();

                $this->info('Rotina efetuada com sucesso!');
           }

       }

    }
}
