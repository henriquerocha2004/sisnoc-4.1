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
                        <div class="card-header" style="background-color: skyblue">
                                <strong>Nova Ação do Chamado: </strong> {{$called->caller_number}}

                                <div class="pull-right">
                                    <small style="color:red" class="text-right"><i>*</i> Campos Obrigatórios</small>
                                    <button type="button" id="btn-popover" class="btn btn-sm btn-primary"  data-toggle="popover" title="Informações do Estabelecimento" data-content="">Aguardando estabelecimento ...</button>
                                </div>
                        </div>
                        <div class="card-body card-block">
                            <form action="{{route('called.storeSubcalled')}}" method="post" class="" enctype="multipart/form-data" autocomplete="off">
                                @csrf
                                <input type="hidden" name="callerId" value="{{$called->id}}">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="establishment_code" class=" form-control-label">Cód. Estabelecimento<i style="color:red">*</i></label>
                                            <input  type="text" id="establishment_code" readonly name="establishment_code" value="{{old('establishment_code') ?? $called->establishment()->first()->establishment_code}}" class="form-control {{ ($errors->has('establishment_code') ? 'is-invalid': '') }}"">
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
                                                <option selected value="{{$called->id_link}}">{{$called->link()->first()->type_link}}</option>
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
                                                            <td>{{$subcaller->created_at_show}}</td>
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
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="status" class=" form-control-label">Status do Estabelecimento<i style="color:red">*</i></label>
                                            <select  name="status" id="status" class="form-control {{ ($errors->has('status') ? 'is-invalid': '') }}">
                                                    <option value="">Selecione</option>
                                                    <option value="1" {{(old('status') == 1 ? 'selected': '')}}>Offline</option>
                                                    <option value="2" {{(old('status') == 2 ? 'selected': '')}}>Funcionando pela redundância</option>
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
                                                <div class="checkbox checkbox2button">
                                                    <label class=" {{ ($errors->has('typeProblem') ? 'is-invalid': '') }}">
                                                         <input type="checkbox" name="typeProblem[]" value="{{$problem->id}}" {{old('typeProblem')[$id] == $problem->id ? 'checked' : ''}}> - {{$problem->problem_description}}
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
                                                <div class="checkbox checkbox2button">
                                                    <label>
                                                        <input type="checkbox" name="actionsTaken[]" value="{{$action->id}}" {{old('actionsTaken')[$id] == $action->id ? 'checked' : ''}}> - {{$action->action_description}}
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
                                        </div>

                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <textarea name="content" id="content" cols="30" rows="10" class="form-control {{ ($errors->has('content') ? 'is-invalid': '') }}">{{old('content')}}</textarea>
                                            @if($errors->has('content'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('content')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>
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
                                        <label class=form-control-label"> Novo Anexo</label>
                                            <input type="file" id="attachment" name="attachment[]" multiple="" class="form-control-file {{ ($errors->has('attachment.*') ? 'is-invalid': ($errors->has('attachment') ? 'is-invalid' : '')) }}">
                                            @if($errors->has('attachment.*') || $errors->has('attachment'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid','force' => true])
                                                    {{$errors->first('attachment.*') }}
                                                    {{$errors->first('attachment') }}
                                                @endcomponent
                                            @endif
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                           <label class=form-control-label"> Direcionar Chamado para: </label>
                                            <select data-is-subcaller="true"  name="next_action" id="next_action" class="form-control {{ ($errors->has('next_action') ? 'is-invalid': '') }}">
                                                    <option value="">Selecione</option>
                                                    <option {{(in_array(2, $idsOpenSubCalled) ? 'disabled' : '')}} value="2" {{old('next_action') == 2 ? 'selected' : ''}}>Abertura de Chamado na operadora</option>
                                                    <option {{(in_array(3, $idsOpenSubCalled) ? 'disabled' : '')}} value="3" {{old('next_action') == 3 ? 'selected' : ''}}>Técnico (Infra)</option>
                                                    <option {{(in_array(4, $idsOpenSubCalled) ? 'disabled' : '')}} value="4" {{old('next_action') == 4 ? 'selected' : ''}}>SEMEP (Infra)</option>
                                                    <option {{(in_array(5, $idsOpenSubCalled) ? 'disabled' : '')}} value="5" {{old('next_action') == 5 ? 'selected' : ''}}>Falta de Energia</option>
                                                    <option {{(in_array(8, $idsOpenSubCalled) ? 'disabled' : '')}} value="8" {{old('next_action') == 8 ? 'selected' : ''}}>Inadiplência</option>
                                            </select>
                                            @if($errors->has('next_action'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('next_action')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>
                                    <div id="divOTRS" class="col-md-4 extra-input" style="display: none">
                                        <div class="form-group">
                                           <label class="form-control-label"> OTRS: </label>
                                           <input  type="text" id="otrs" name="otrs" value="{{old('otrs')}}" class="form-control {{ ($errors->has('otrs') ? 'is-invalid': '') }}"">
                                            @if($errors->has('otrs'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('otrs')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>
                                    <div id="divSemep" class="col-md-4 extra-input" style="display: none">
                                        <div class="form-group">
                                           <label class="form-control-label"> SEMEP: </label>
                                           <input  type="text" id="sisman" name="sisman" value="{{old('sisman')}}" class="form-control {{ ($errors->has('sisman') ? 'is-invalid': '') }}"">
                                            @if($errors->has('sisman'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('sisman')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>
                                    <div id="divHrUP" class="col-md-4 extra-input" style="display: none">
                                        <div class="form-group">
                                           <label class="form-control-label"> Horário da Normalização: </label>
                                           <input  type="text" id="hr_up" name="hr_up" value="{{old('hr_up')}}" class="form-control {{ ($errors->has('hr_up') ? 'is-invalid': '') }}"">
                                            @if($errors->has('hr_up'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('hr_up')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>
                                    <div id="divCauseProb" class="col-md-4 extra-input" style="display: none">
                                        <div class="form-group">
                                           <label class="form-control-label"> Causa do Problema: </label>
                                           <select  name="id_problem_cause" id="id_problem_cause" class="form-control {{ ($errors->has('id_problem_cause') ? 'is-invalid': '') }}">
                                                <option value="">Selecione</option>
                                                @foreach ($categoryProblems as $category)
                                                    <optgroup label="{{ $category->description_category }}">
                                                        @foreach ($category->problems()->get() as $problem)
                                                            <option value="{{ $problem->id }}">{{ $problem->description_cause}}</option>
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
                                    <div id="divCallTel" class="col-md-4 extra-input" style="display: none">
                                        <div class="form-group">
                                           <label for="call_telecommunications_company" class="form-control-label"> Protocolo Operadora: </label>
                                           <input  type="text" id="call_telecommunications_company" name="call_telecommunications_company" value="{{old('call_telecommunications_company')}}" class="form-control {{ ($errors->has('hr_up') ? 'is-invalid': '') }}"">
                                            @if($errors->has('call_telecommunications_company'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('call_telecommunications_company')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>
                                    <div id="divDeadLine" class="col-md-4 extra-input" style="display: none">
                                        <div class="form-group">
                                           <label for="deadline" class="form-control-label"> Prazo de Normalização: </label>
                                           <input  type="text" id="deadline" name="deadline" value="{{old('deadline')}}" class="form-control {{ ($errors->has('deadline') ? 'is-invalid': '') }}"">
                                            @if($errors->has('deadline'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('deadline')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>
                        </div>
                        <div class="card-footer">
                            <button id="btn-save" type="submit" disabled class="btn btn-primary btn-sm disabled">
                                <i class="fa fa-dot-circle-o"></i> Salvar
                            </button>
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
