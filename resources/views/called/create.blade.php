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
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-12">
                                        <strong>Novo </strong> Chamado
                                        <small style="color:red" class="text-right"><i>*</i> Campos Obrigatórios</small>
                                        <button type="button" id="btn-popover" class="btn btn-sm btn-primary" style="margin-left: 45%" data-toggle="popover" title="Informações do Estabelecimento" data-content="">Aguardando estabelecimento ...</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body card-block">
                            <form action="{{route('called.store')}}" method="post" class="" autocomplete="off">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="establishment_code" class=" form-control-label">Cód. Estabelecimento<i style="color:red">*</i></label>
                                            <input  type="text" id="establishment_code" name="establishment_code" value="{{old('establishment_code')}}" class="form-control {{ ($errors->has('establishment_code') ? 'is-invalid': '') }}"">
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

                                            </select>
                                            @if($errors->has('id_link'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('id_link')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>
                        </div>
                        <div id="called" style="display: none">
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
                                            <input readonly  type="text" id="hr_down" name="hr_down" value="{{old('hr_down')}}" class="form-control {{ ($errors->has('hr_down') ? 'is-invalid': '') }}">
                                            @if($errors->has('hr_down'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('hr_down')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group ml-4 {{ ($errors->has('typeProblem') ? 'is-invalid': '') }}">
                                           <label class="form-control-label"> Tipo de Problema</label>
                                            @php $id = 0; @endphp

                                            @foreach ($typeProblems as $problem)
                                                <div class="checkbox checkbox2button">
                                                    <label>
                                                         <input type="checkbox" name="typeProblem[]" value="{{$problem->id}}" {{old('typeProblem')[$id] == $problem->id ? 'checked' : ''}}> - {{$problem->problem_description}}
                                                    </label>
                                                </div>
                                             @php$id++@endphp
                                            @endforeach

                                            @if($errors->has('typeProblem'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
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
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('actionsTaken')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                           <label class="form-control-label"> Observações </label>
                                            <textarea name="content" id="content" cols="30" rows="10" class="form-control {{ ($errors->has('content') ? 'is-invalid': '') }}">{{old('content')}}</textarea>
                                            @if($errors->has('content'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('content')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                           <label class="form-control-label"> Direcionar Chamado para: </label>
                                            <select  name="next_action" id="next_action" class="form-control {{ ($errors->has('next_action') ? 'is-invalid': '') }}">
                                                    <option value="">Selecione</option>
                                                    <option value="1" {{old('next_action') == 1 ? 'selected' : ''}}>Finalizar Atendimento</option>
                                                    <option value="2" {{old('next_action') == 2 ? 'selected' : ''}}>Abertura de Chamado na operadora</option>
                                                    <option value="3" {{old('next_action') == 3 ? 'selected' : ''}}>Técnico (Infra)</option>
                                                    <option value="4" {{old('next_action') == 4 ? 'selected' : ''}}>SEMEP (Infra)</option>
                                                    <option value="5" {{old('next_action') == 5 ? 'selected' : ''}}>Falta de Energia</option>
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
    <script src="{{url('/js/ckeditor/ckeditor.js')}}"></script>
    <script>
       $(function(){
        var popoverData = null;

        //Verify if exists return of validation

        if($('#establishment_code').val() !== ''){
             getLinks($('#establishment_code').val());
             $("#called").show(800);
        }


        $("#hr_down, #hr_up, #deadline").flatpickr({
            enableTime:true,
            dateFormat: "d/m/Y H:i",
            locale: "pt",
            {{--  minDate: "today"  --}}
        });

        // ativated editor
        CKEDITOR.replace('content');

        // action when the user enters the establishment code
            $("#establishment_code").focusout(function(){
                 var establishmentCode = $(this).val();

                 if(establishmentCode !== ''){
                    getLinks(establishmentCode);
                 }else{
                    $("#called").hide(800);
                 }
            });

            //action when the user selects a link
            $("#id_link").change(function(){

                var idLink = $(this).val();

                if(idLink !== ''){
                    $.get('{{url('verify-open-called')}}', {id_link: idLink}, function(r){
                        if(r.response){
                            $.alert({
                                title: "Aviso | Sisnoc",
                                content: "Existe Chamado aberto para esse link!"
                            })
                        }else{
                            //First insert information into popover
                            var dataContent =
                            `
                            <div>
                                <p>Endereço: ${popoverData.establishment.info.address}<p>
                                <p>Cidade: ${popoverData.establishment.info.city}<p>
                                <p>UF: ${popoverData.establishment.info.state}<p>
                                <p>Gerente: ${popoverData.establishment.info.manager_name}<p>
                                <p>Contato Gerente: ${popoverData.establishment.info.manager_contact}<p>
                                <p>Gerente Regional: ${popoverData.establishment.regionalManager.name}<p>
                                <p>Contato Gerente Regional: ${popoverData.establishment.regionalManager.contact}<p>
                            </div>
                            `;

                            $("#btn-popover").attr('data-content', dataContent);
                            $("#btn-popover").html('Informações do Estabelecimento');

                            $(function () {
                                $('[data-toggle="popover"]').popover({
                                    html: true
                                })
                            });

                            $("#called").show(800);
                            $("#btn-save").attr('disabled', false).removeClass('disabled');
                        }
                    });
                }
            });

            $("#next_action").change(function(){
                var action = $(this).val();

                if(action !== ''){
                    $(".extra-input").hide();

                    switch(action){
                        case '1':
                            $("#divHrUP").show();
                        break;
                        case '2':
                            $("#divCallTel").show();
                            $("#divDeadLine").show();
                        break;
                        case '3':
                            $("#divOTRS").show();
                        break;
                        case '4':
                            $("#divSemep").show();
                        break;
                        default:
                            $(".extra-input").hide();
                        break
                    }
                }else{
                    $(".extra-input").hide();
                }
            });


            function getLinks(establishmentCode){
                $.get('{{url('get-links-establishment')}}', {establishment_code: establishmentCode}, function(r){

                    if(r.response){

                        var options = '<option value="">Selecione</option>';
                        popoverData = r;
                        var old = {{old('id_link') ?? 0 }};
                        $.each(r.links, function(k, v){
                            options += `<option value="${v.id}" ${(old == v.id ? 'selected' : '')}>${v.type_link} - ${v.link_identification}</option>`;
                        });

                        $("#id_link").html(options);
                    }else{
                        $.alert({
                            title: "Aviso | Sisnoc",
                            content: r.message
                        });
                    }
                }, 'json');
            }


       });
    </script>
@endsection
