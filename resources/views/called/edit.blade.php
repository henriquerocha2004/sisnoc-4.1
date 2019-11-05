@extends('master.master')

@section('content')

<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
             <div class="row">
                <div class="col-md-12">
                    @if (session('alert'))
                        @component('compoments.message', ['type' => session('alert')['messageType']])
                            {{session('alert')['message']}}
                        @endcomponent
                    @endif

                    <div class="card">
                        <div class="card-header" style="background-color: {{($called->status == 1 || $called->status == 7 ? 'lightsalmon' : 'gold')}}">
                                <strong>Chamado </strong> {{$called->caller_number}} - Situação: <strong>{{$called->status_show}}</strong>

                                <div class="pull-right">
                                    <small style="color:red" class="text-right"><i>*</i> Campos Obrigatórios</small>
                                    <button type="button" id="btn-popover" class="btn btn-sm btn-primary"  data-toggle="popover" title="Informações do Estabelecimento" data-content="">Aguardando estabelecimento ...</button>
                                </div>
                        </div>
                        <div class="card-body card-block">
                            <form action="{{route('called.update', $called->id)}}" method="post" class="" enctype="multipart/form-data" autocomplete="off">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="lastSubcallerId" id="lastSubcallerId" value="{{$lastSubCaller->id}}">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="establishment_code" class=" form-control-label">Cód. Estabelecimento<i style="color:red">*</i></label>
                                            <input  type="text" id="establishment_code" readonly name="establishment_code" value="{{old('establishment_code') ?? $called->establishment()->first()->establishment_code}}" class="form-control {{ ($errors->has('establishment_code') ? 'is-invalid': '') }}">
                                            @if($errors->has('establishment_code'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('establishment_code')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="id_link" class=" form-control-label">Tipo de link<i style="color:red">*</i></label>
                                            <select  name="id_link" id="id_link" class="form-control {{ ($errors->has('id_link') ? 'is-invalid': '') }}">
                                                <option selected value="{{$called->id_link}}">{{$called->link()->first()->type_link}} - {{$called->link()->first()->link_identification}}</option>
                                            </select>
                                            @if($errors->has('id_link'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('id_link')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                      <label class=" form-control-label mt-1">Ações Tomadas</label>
                                        @if ($called->status != 1 && $called->status != 7)
                                            <a href="{{url('new-sub-caller', [$called->id])}}" class="btn btn-sm btn-primary pull-right" > + Nova Ação</a>
                                        @endif

                                        <div class="table-responsive">
                                            <table class="table table-borderless table-data3">
                                                <thead>
                                                    <tr>
                                                        <th>Ação</th>
                                                        <th>Atendente</th>
                                                        <th>Horário</th>
                                                        <th>Situação local</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($called->subCallers()->get() as $subcaller)
                                                        <tr>
                                                            <td>{{$subcaller->type_show}}</td>
                                                            <td>{{$subcaller->user()->first()->name}}</td>
                                                            <td>{{$subcaller->created_at}}</td>
                                                            <td>{{$subcaller->status_establishment_show}}</td>
                                                            <td data-url-show="{{route('called.edit', [$called->id, $subcaller->id])}}" style="cursor: pointer" class="showSubSaller {{($subcaller->status_show == 'Aberto' ? 'process' : 'denied')}}">{{$subcaller->status_show}}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                        <div id="called" class="mt-4">
                            <div class="row">

                                <div class="col-md-12 mt-3 mb-5 text-left">
                                    <h3>Ação: {{$lastSubCaller->type_show}}</h3>
                                </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="status" class=" form-control-label">Status do Estabelecimento<i style="color:red">*</i></label>
                                            <select style="background: #eee; pointer-events: none; touch-action: none"  name="status" id="status" class="form-control {{ ($errors->has('status') ? 'is-invalid': '') }}">
                                                    <option value="">Selecione</option>
                                                    <option value="1" {{(old('status') == 1 ? 'selected': ($lastSubCaller->status_establishment == 1 ? 'selected' : ''))}}>Offline</option>
                                                    <option value="2" {{(old('status') == 2 ? 'selected': ($lastSubCaller->status_establishment == 2 ? 'selected' : ''))}}>Funcionando pela redundância</option>
                                            </select>
                                            @if($errors->has('status'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('status')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="hr_down" class=" form-control-label">Momento do Incidente<i style="color:red">*</i></label>
                                            <input readonly  type="text" id="hr_down" name="hr_down" value="{{old('hr_down') ?? $called->hr_down_show }}" class="form-control {{ ($errors->has('hr_down') ? 'is-invalid': '') }}">
                                            @if($errors->has('hr_down'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('hr_down')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group ml-4">
                                           <label class="form-control-label"> Tipo de Problema</label>
                                            @php $id = 0; @endphp

                                            @foreach ($typeProblems as $problem)

                                                @foreach ($lastSubCaller->typeProblem()->get() as $ProblemDB)
                                                    @php
                                                        $checked = ($ProblemDB->id_problem_type == $problem->id ? 'checked' : '');
                                                        if($checked == 'checked')
                                                            break;
                                                    @endphp
                                                @endforeach

                                                <div class="checkbox checkbox2button">
                                                    <label class=" {{ ($errors->has('typeProblem') ? 'is-invalid': '') }}">
                                                         <input type="checkbox" name="typeProblem[]" value="{{$problem->id}}" {{old('typeProblem')[$id] == $problem->id ? 'checked' : $checked }}> - {{$problem->problem_description}}
                                                    </label>
                                                </div>
                                             @php$id++@endphp
                                            @endforeach

                                            @if($errors->has('typeProblem'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid', 'force' => true ])
                                                    {{$errors->first('typeProblem')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group {{ ($errors->has('actionsTaken') ? 'is-invalid': '') }}">
                                           <label class="form-control-label"> Ação Tomada</label>

                                            @php $id = 0; @endphp
                                            @foreach ($actionsTaken as $action)
                                                @foreach ($lastSubCaller->actionTake() as $actionDB)
                                                    @php
                                                        $checked = ($actionDB->id_action_taken == $action->id ? 'checked' : '');
                                                        if($checked == 'checked')
                                                            break;
                                                    @endphp
                                                @endforeach

                                                <div class="checkbox checkbox2button">
                                                    <label>
                                                        <input type="checkbox" name="actionsTaken[]" value="{{$action->id}}" {{old('actionsTaken')[$id] == $action->id ? 'checked': $checked }}> - {{$action->action_description}}
                                                    </label>
                                                </div>

                                                @php $id++; @endphp
                                            @endforeach

                                            @if($errors->has('actionsTaken'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid', 'force' => true])
                                                    {{$errors->first('actionsTaken')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div id="btn-notes" class="form-group">
                                            <label class="form-control-label"> Observações </label><br>
                                            @if (!empty($lastSubCaller->notes))
                                                @foreach ($lastSubCaller->notes as $note)
                                                    <button type="button" data-id-note="{{$note->id}}" class="btn btn-sm btn-success btn-notes mb-2"><i class="fa fa-sticky-note"></i> Nota de {{ $note->subCaller()->first()->user()->first()->name }}<br><small>{{$note->created_at}}</small></button>
                                                @endforeach
                                            @endif
                                        </div>

                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <textarea name="content" id="content" cols="30" rows="10" class="form-control {{ ($errors->has('content') ? 'is-invalid': '') }}">{{old('content') ?? ($lastSubCaller->notes()->first()->content ?? '') }}</textarea>
                                            @if($errors->has('content'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('content')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>
                                    @if ($called->status != 1 && $called->status != 7)
                                        <div class="col-md-12 mb-2">
                                            <div class="btn-group pull-right">
                                                <button type="button" id="new-note" class="btn btn-sm btn-success">Nova Nota</button>
                                                <button type="button" id="save-note" disabled class="btn btn-sm btn-success"><i class="fa fa-save"></i> Salvar Nota</button>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-md-12 mb-4 ">
                                        <div id="images" class="mb-3">
                                            @if (count($called->attachments()->get()) > 0)
                                                <label class=form-control-label"> Anexos Salvos</label><br>
                                                <div class="btn-group">
                                                    @foreach ($called->attachments()->get() as $image)
                                                        <button type="button" data-url-image="{{url(Storage::url($image->url_attachment))}}" class="btn btn-primary image">Anexo {{$image->id}}</button>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                        @if ($called->status != 1 && $called->status != 7)
                                            <label class=form-control-label"> Novo Anexo</label>
                                            <input type="file" id="attachment" name="attachment[]" multiple="" class="form-control-file {{ ($errors->has('attachment.*') ? 'is-invalid': ($errors->has('attachment') ? 'is-invalid' : '')) }}">
                                            @if($errors->has('attachment.*') || $errors->has('attachment'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid','force' => true])
                                                    {{$errors->first('attachment.*') }}
                                                    {{$errors->first('attachment') }}
                                                @endcomponent
                                            @endif
                                        @endif

                                    </div>
                                    @if($called->status != 1 && $called->status != 7)
                                        <div class="col-md-4">
                                            <div class="form-group">
                                            <label class="form-control-label"> Próxima Ação: </label>
                                                <select data-is-subcaller="true" name="next_action" id="next_action" class="form-control {{ ($errors->has('next_action') ? 'is-invalid': '') }}">
                                                    <option value="">Selecione</option>
                                                    @if($called->status == 2 && empty($lastSubCaller->call_telecommunications_company_number))
                                                        <option value="10" {{old('next_action') == 10 ? 'selected' : ''}}>Atualizar chamado com dados da Operadora</option>
                                                    @else
                                                        <option value="1" {{old('next_action') == 1 ? 'selected' : ''}}>Finalizar Chamado</option>
                                                        <option value="9" {{old('next_action') == 9 ? 'selected' : ''}}>Finalizar Ação</option>
                                                    @endif
                                                </select>
                                                @if($errors->has('next_action'))
                                                    @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                        {{$errors->first('next_action')}}
                                                    @endcomponent
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <div id="divOTRS" class="col-md-4  {{(empty($lastSubCaller->otrs) ? 'd-none' : '')}} ">
                                        <div class="form-group">
                                           <label class="form-control-label"> OTRS: </label>
                                           <input readonly type="text" id="otrs" name="otrs" value="{{old('otrs') ?? $lastSubCaller->otrs}}" class="form-control {{ ($errors->has('otrs') ? 'is-invalid': '') }}">
                                            @if($errors->has('otrs'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('otrs')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>
                                    <div id="divSemep" class="col-md-4  {{(empty($lastSubCaller->sisman) ? "d-none" : '')}}" >
                                        <div class="form-group">
                                           <label class="form-control-label"> SEMEP: </label>
                                           <input readonly type="text" id="sisman" name="sisman" value="{{old('sisman') ?? $lastSubCaller->sisman}}" class="form-control {{ ($errors->has('sisman') ? 'is-invalid': '') }}">
                                            @if($errors->has('sisman'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('sisman')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>
                                <div id="divHrUP" class="col-md-4 extra-input" style="display: {{(empty($called->hr_up) ? 'none' : 'block')}}" >
                                        <div class="form-group">
                                           <label class="form-control-label"> Horário da Normalização: </label>
                                            <input {{(empty($called->hr_up) ? '' : 'readonly')}}  type="text" id="{{(empty($called->hr_up) ? 'hr_up' : '')}}" name="hr_up" value="{{old('hr_up', $called->hr_up)}}" class="form-control {{ ($errors->has('hr_up') ? 'is-invalid': '') }}">
                                            @if($errors->has('hr_up'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('hr_up')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>

                                    <div id="divCauseProb" class="col-md-4 extra-input">
                                        <div class="form-group">
                                           <label class="form-control-label"> Causa do Problema: </label>
                                           <select style="{{($called->status == 1 || $called->status == 7 ? 'background: #eee; pointer-events: none; touch-action: none' : ''  )}}"  name="id_problem_cause"
                                            id="id_problem_cause" class="form-control {{ ($errors->has('id_problem_cause') ? 'is-invalid': '') }}">
                                                <option value="">Selecione</option>
                                                @foreach ($categoryProblems as $category)
                                                    <optgroup label="{{ $category->description_category }}">
                                                        @foreach ($category->problems()->get() as $problem)
                                                            <option {{($called->id_problem_cause == $problem->id ? 'selected' : '')}} value="{{ $problem->id }}">{{ $problem->description_cause}}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @endforeach
                                           </select>
                                            @if($errors->has('id_problem_cause'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('id_problem_cause')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>
                                    <div id="divCallTel" class="col-md-4 {{(empty($lastSubCaller->call_telecommunications_company_number) && $called->status != 2 ? 'd-none' : '')}}" >
                                        <div class="form-group">
                                           <label for="call_telecommunications_company" class="form-control-label"> Protocolo Operadora: </label>
                                           <input {{ (!empty($lastSubCaller->call_telecommunications_company_number) ? 'readonly' : '') }} type="text" id="call_telecommunications_company" name="call_telecommunications_company" value="{{old('call_telecommunications_company') ?? $lastSubCaller->call_telecommunications_company_number}}" class="form-control {{ ($errors->has('hr_up') ? 'is-invalid': '') }}">
                                            @if($errors->has('call_telecommunications_company'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('call_telecommunications_company')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>
                                    <div id="divDeadLine" class="col-md-4" style="display: {{(empty($lastSubCaller->deadline) && $called->status != 2 ? 'none' : 'block')}}">
                                        <div class="form-group">
                                           <label for="deadline" class="form-control-label"> Prazo de Normalização: </label>
                                           <input {{ (!empty($lastSubCaller->deadline) ? 'readonly' : '') }} type="text" id="{{(empty($lastSubCaller->deadline) ? 'deadline' : '')}}" name="deadline" value="{{old('deadline') ?? $lastSubCaller->deadline}}" class="form-control {{ ($errors->has('deadline') ? 'is-invalid': '') }}">
                                            @if($errors->has('deadline'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('deadline')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>
                        </div>
                        <div class="card-footer">
                            @if($called->status != 1 && $called->status != 7)
                                <button id="btn-save" type="submit" disabled class="btn btn-primary btn-sm disabled">
                                    <i class="fa fa-dot-circle-o"></i> Salvar
                                </button>
                            @endif
                        </div>
                    </form>
                    </div>
                </div>

             </div>
        </div>
    </div>
</div>

@endsection

@section('js')
    <script src="{{url('/l10n/pt.js')}}"></script>
    <script>
       var routes = {
           "insertNotes" : '{{url('insert-notes')}}',
           "getNotes" : '{{url('get-notes')}}',
           "verifyOpenCalled" :'{{url('verify-open-called')}}',
           "getLinksEstablishment" : '{{url('get-links-establishment')}}'
       }
    </script>
    <script src="{{url('/js/caller/callerEdit.js')}}"></script>
@endsection
