<?php

namespace App\Http\Controllers;

use DB;
use App\Http\Requests\CalledRequest;
use App\Models\ActionTake;
use App\Models\ActionTakeCalled;
use App\Models\Attachment;
use App\Models\Called;
use App\Models\CategoryProblem;
use App\Models\Establishment;
use App\Models\Links;
use App\Models\Notes;
use App\Models\SubCaller;
use App\Models\TypeProblem;
use App\Models\TypeProblemCalled;
use App\Utils\DateUtils;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CalledController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('called.index');
    }

    /**
     * Generate dataTables at index
     */
    public function table()
    {
        $calleds = Called::join('establishment', 'called.id_establishment', '=', 'establishment.id')
            ->join('links', 'called.id_link', '=', 'links.id')
            ->join('users', 'called.id_user_open', '=', 'users.id')
            ->select(
                [
                    'called.id',
                    'called.caller_number',
                    'establishment.establishment_code',
                    'links.type_link',
                    'called.next_action',
                    'users.name'
                ]
            );
        return DataTables::of($calleds)->make(true);
    }

    /**
     * Function that returns the links of the informed establishment
     */
    public function getLinks(Request $request)
    {
        $result['response'] = false;

        $establishment = Establishment::where(['establishment_code' => $request->establishment_code])->first();

        if ($establishment != null) {

            $idEstablishment = $establishment->id;
            $links = Links::select(['id', 'link_identification', 'type_link'])
                ->where(['establishment_id' => $idEstablishment])->get();

            if (count($links) > 0) {
                $result['response'] = true;
                $result['links'] = $links;
                $result['establishment']['info'] = $establishment;
                $result['establishment']['regionalManager'] = $establishment->regionalManager()->first();
                $result['establishment']['technicalManager'] = $establishment->technicalManager()->first();
            } else {
                $result['message'] = "Esse estabelecimento não possui links cadastrados!";
            }
        } else {
            $result['message'] = "Estabelecimento não encontrado!";
        }

        return response()->json($result);
    }

    /**
     * function that checks for open calls
     */
    public function verifyOpenCalled(Request $request)
    {
        $result['response'] = false;
        $link = Links::find($request->id_link);

        $called = $link->called()
            ->where('status', '<>', 1)
            ->where('status', '<>', 7)
            ->get();

        if (count($called) > 0) {
            $result['response'] = true;
        }

        return response()->json($result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $typeProblems = TypeProblem::select(['id', 'problem_description'])->get();
        $actionsTaken = ActionTake::select(['id', 'action_description'])->get();
        $categoryProblems = CategoryProblem::select(['id', 'description_category'])->get();

        return view('called.create', [
            'typeProblems' => $typeProblems,
            'actionsTaken' => $actionsTaken,
            'categoryProblems' => $categoryProblems
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CalledRequest $request)
    {
        DB::beginTransaction();

        try {

            $establishment = Establishment::select('id')->where(['establishment_code' => $request->establishment_code])->first();
            $called = new Called();
            $called->fill($request->all());
            $called->caller_number = date('Ymd') . rand(50, 500);
            $called->status = $request->next_action;
            $called->id_problem_cause = 1;
            $called->id_establishment = $establishment->id;
            $called->id_user_open = auth()->user()->id;

            if (!$called->save()) {
                throw new Exception("Houve uma falha ao salvar o chamado");
            }

            $subcaller = new SubCaller();
            $subcaller->id_user = auth()->user()->id;
            $subcaller->id_caller = $called->id;
            $subcaller->status = 'open';

            switch ($request->next_action) {
                case '1':
                    $closeSubCaller = $this->setCloseSubCaller($subcaller, $request, $called->id);

                    if (!$closeSubCaller) {
                        throw new Exception("Falha ao inserir Sub-Chamado!");
                    }

                    $closeCalled = $this->setCloseCalled($called, $request);

                    if (!$closeCalled) {
                        throw new Exception("Falha ao inserir o Chamado!");
                    }

                    break;
                case '2':

                    $insertSubcaller = $this->setTelecomunicationsCompany($subcaller, $request, $called->id);

                    if (!$insertSubcaller) {
                        new Exception("Houve uma falha ao inserir os dados da operadora!");
                    }

                    break;
                case '3':

                    $insertSubcaller = $this->setOtrs($subcaller, $request, $called->id);

                    if (!$insertSubcaller) {
                        new Exception("Houve uma falha ao inserir os dados do Otrs!");
                    }

                    break;
                case '4':

                    $insertSubcaller = $this->setSemep($subcaller, $request, $called->id);

                    if (!$insertSubcaller) {
                        new Exception("Houve uma falha ao inserir os dados do Semep!");
                    }

                    break;
                case '5':

                    $insertSubcaller = $this->setEnergyFault($subcaller, $request, $called->id);

                    if (!$insertSubcaller) {
                        new Exception("Houve uma falha ao inserir os dados relacionados a falta de energia!");
                    }

                    break;
                case '8':

                    $insertSubcaller = $this->setDebtor($subcaller, $request, $called->id);

                    if (!$insertSubcaller) {
                        new Exception("Houve uma falha ao inserir os dados relacionados a Inadiplência!");
                    }

                    break;
                default:
                    new Exception("Essa solicitação não é válida!");
                    break;
            }


            //Save the type problem
            $typeProblem = $this->setTypeProblem($request, $subcaller);

            if (!$typeProblem) {
                new Exception("Houve uma falha ao salvar os tipos de problemas!");
            }

            //Save a action taken
            $actionTaken = $this->setActionTaken($request, $subcaller);

            if (!$actionTaken) {
                new Exception("Houve uma falha ao salvar as ações tomadas!");
            }

            //Save notes
            if (!empty($request->content)) {
                $notes = $this->setNotes($request, $subcaller);

                if (!$notes) {
                    new Exception("Houve uma falha ao salvar a nota!");
                }
            }

            //Save attachment
            if (!empty($request->file('attachment'))) {
                $upload = $this->uploads($request, $called->id);

                if (!$upload) {
                    new Exception("Houve uma falha ao fazer o upload das imagens!");
                }
            }


            DB::commit();
            return redirect()->route('called.index')->with('alert', ['messageType' => 'success', 'message' => 'Chamado nº ' . $called->caller_number . ' gerado com sucesso!']);
        } catch (Exception $e) {
            DB::rollback();
            return back()->withInput()->with('alert', ['messageType' => 'danger', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    { }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    { }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    { }

    private function setTelecomunicationsCompany(SubCaller $subcaller, $request, $idCalled)
    {

        $subcaller->type = 2;
        $subcaller->call_telecommunications_company_number = $request->call_telecommunications_company;
        $subcaller->deadline = $request->deadline;
        $subcaller->hr_open_call_telecommunications_company = $request->hr_open_call_telecommunications_company;

        return $subcaller->save();
    }

    private function setOtrs(SubCaller $subcaller, $request, $idCalled)
    {
        $subcaller->type = 3;
        $subcaller->otrs = $request->otrs;
        return $subcaller->save();
    }

    private function setSemep(SubCaller $subcaller, $request, $idCalled)
    {
        $subcaller->type = 4;
        $subcaller->sisman = $request->sisman;
        return $subcaller->save();
    }

    private function setEnergyFault(SubCaller $subcaller, $request, $idCalled)
    {
        $subcaller->type = 5;
        return $subcaller->save();
    }

    private function setDebtor(SubCaller $subcaller, $request, $idCalled)
    {
        $subcaller->type = 8;
        return $subcaller->save();
    }

    private function setCloseSubCaller(SubCaller $subcaller, $request, $idCalled = null, $newRegister = false)
    {
        $subcaller->status = 'closed';
        $subcaller->id_user_close = auth()->user()->id;

        if ($newRegister == true) {
            $subcaller->type = 6;
        }

        return $subcaller->save();
    }


    private function setCloseCalled(Called $called, $request)
    {
        $called->hr_up = $request->hr_up;
        $called->id_problem_cause = $request->id_problem_cause;
        $called->id_user_close = auth()->user()->id;
        $called->status = 1;
        $called->downtime = DateUtils::calcDowntime($called->hr_down, $called->hr_up);
        $called->work_downtime = DateUtils::calcWorkDowntime($called->hr_up, $called->hr_down, $called->downtime);

        return $called->save();
    }

    private function setTypeProblem($request, SubCaller $subcaller)
    {

        $typeProblem = new TypeProblemCalled();

        foreach ($request->typeProblem as $problem) {

            $typeProblem->id_called = $subcaller->id;
            $typeProblem->id_problem_type = $problem;

            if (!$typeProblem->save()) {
                return false;
            }
        }

        return true;
    }

    private function setActionTaken($request, SubCaller $subcaller)
    {

        $actionTaken = new ActionTakeCalled();

        foreach ($request->actionsTaken as $action) {

            $actionTaken->id_caller = $subcaller->id;
            $actionTaken->id_action_taken = $action;

            if (!$actionTaken->save()) {
                return false;
            }
        }

        return true;
    }

    private function setNotes($request, SubCaller $subcaller)
    {

        $notes = new Notes();
        $notes->id_sub_caller = $subcaller->id;
        $notes->content = $request->content;

        return $notes->save();
    }

    private function uploads($request, $idCalled)
    {
        $attachment = new Attachment();
        $attachment->id_called = $idCalled;
        $attachment->url_attachment = $request->file('attachment')->store('called/' . $idCalled);
        return $attachment->save();
    }
}
