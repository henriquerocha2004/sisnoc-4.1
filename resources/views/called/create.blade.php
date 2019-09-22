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
                            <strong>Novo </strong> Chamado
                            <small style="color:red" class="text-right"><i>*</i> Campos Obrigatórios</small>
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
                                            @if($errors->has('type_link'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('id_link')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>
                        </div>
                        <div class="">
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button" id="btn-popover" class="btn btn-lg btn-primary" style="margin-left: 75%;margin-bottom: 2%;margin-top: 2%" data-toggle="popover" title="Informações do Estabelecimento" data-content="">Mais informações ...</button>
                                </div>
                            </div>
                            <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="id_link" class=" form-control-label">Status do Estabelecimento<i style="color:red">*</i></label>
                                            <select  name="id_link" id="id_link" class="form-control {{ ($errors->has('id_link') ? 'is-invalid': '') }}">
                                                    <option value="">Selecione</option>
                                                    <option value="1">Offline</option>
                                                    <option value="2">Funcionando pela redundância</option>
                                            </select>
                                            @if($errors->has('type_link'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('id_link')}}
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
                                        <div class="form-group ml-4">
                                           <label class="form-control-label"> Tipo de Problema</label>
                                            @foreach ($typeProblems as $problem)
                                                <div class="checkbox checkbox2button">
                                                    <label>
                                                         <input type="checkbox" name="typeProblem[]" value="{{$problem->id}}"> - {{$problem->problem_description}}   
                                                    </label>
                                                </div>             
                                            @endforeach
                                           
                                            @if($errors->has('hr_down'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('hr_down')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                           <label class="form-control-label"> Ação Tomada</label>
                                            @foreach ($actionsTaken as $action)
                                                <div class="checkbox checkbox2button">
                                                    <label>
                                                         <input type="checkbox" name="actionsTaken[]" value="{{$action->id}}"> - {{$action->action_description}}   
                                                    </label>
                                                </div>             
                                            @endforeach
                                           
                                            @if($errors->has('hr_down'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('hr_down')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                           <label class="form-control-label"> Observações </label>
                                           <textarea name="content" id="content" cols="30" rows="10"></textarea>
                                            @if($errors->has('hr_down'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('hr_down')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                           <label class="form-control-label"> Direcionar Chamado para: </label>
                                           <textarea name="content" id="content" cols="30" rows="10"></textarea>
                                            @if($errors->has('hr_down'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('hr_down')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>

                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" disabled class="btn btn-primary btn-sm disabled">
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

        $("#hr_down").flatpickr({
            enableTime:true,
            dateFormat: "d/m/Y H:i",
            locale: "pt",
            {{--  minDate: "today"  --}}
        });

        CKEDITOR.replace('content');

        // action when the user enters the establishment code
            $("#establishment_code").focusout(function(){
                 var establishmentCode = $(this).val();

                 if(establishmentCode !== ''){

                    $.get('{{url('get-links-establishment')}}', {establishment_code: establishmentCode}, function(r){

                        if(r.response){

                            var options = '<option value="">Selecione</option>';
                            popoverData = r;
                            $.each(r.links, function(k, v){
                                options += `<option value="${v.id}">${v.type_link} - ${v.link_identification}</option>`;
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

                            $(function () {
                                $('[data-toggle="popover"]').popover({
                                    html: true
                                })
                            });
                        }
                    });
                }
            });
       });
    </script>
@endsection
