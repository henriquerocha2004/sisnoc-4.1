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
                            <strong>Cadastro </strong> de Link
                            <small style="color:red" class="text-right"><i>*</i> Campos Obrigatórios</small>
                        </div>
                        <div class="card-body card-block">
                            <form action="{{route('links.store')}}" method="post" class="" autocomplete="off">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="name" class=" form-control-label">Tipo de link<i style="color:red">*</i></label>
                                            <select  name="type_link" id="type_link" class="form-control {{ ($errors->has('type_link') ? 'is-invalid': '') }}">
                                                <option value="">Selecione</option>
                                                @foreach ($typeLink as $desc)
                                                    <option value="{{$desc}}" {{(old('type_link') == $desc ? 'selected' : '')}} >{{$desc}}</option>
                                                @endforeach
                                            </select>
                                            @if($errors->has('type_link'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('type_link')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="contact" class=" form-control-label">Idenrificação<i style="color:red">*</i></label>
                                            <input  type="text" value="{{old('link_identification')}}" id="link_identification" name="link_identification"  class="form-control {{ ($errors->has('link_identification') ? 'is-invalid': '') }}">
                                            @if($errors->has('link_identification'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('link_identification')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="bandwidth" class=" form-control-label">Banda<i style="color:red">*</i></label>
                                            <input  type="text" value="{{old('bandwidth')}}" id="bandwidth" name="bandwidth" class="form-control {{ ($errors->has('bandwidth') ? 'is-invalid': '') }}">
                                            @if($errors->has('bandwidth'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('bandwidth')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="telecommunications_company" class=" form-control-label">Operadora<i style="color:red">*</i></label>
                                            <input  type="text" value="{{old('telecommunications_company')}}" id="telecommunications_company" name="telecommunications_company" class="form-control {{ ($errors->has('telecommunications_company') ? 'is-invalid': '') }}">
                                            @if($errors->has('telecommunications_company'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('telecommunications_company')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="monitoring_ip" class=" form-control-label">IP de monitoramento<i style="color:red">*</i></label>
                                            <input  type="text" value="{{old('monitoring_ip')}}" id="monitoring_ip" name="monitoring_ip" class="form-control {{ ($errors->has('monitoring_ip') ? 'is-invalid': '') }}">
                                            @if($errors->has('monitoring_ip'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('monitoring_ip')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="installed_router_model" class=" form-control-label">IP de monitoramento<i style="color:red">*</i></label>
                                            <input  type="text" value="{{old('installed_router_model')}}" id="installed_router_model" name="installed_router_model" class="form-control {{ ($errors->has('installed_router_model') ? 'is-invalid': '') }}">
                                            @if($errors->has('installed_router_model'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('installed_router_model')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="local_ip_router" class=" form-control-label">Ip Lan Roteador<i style="color:red">*</i></label>
                                            <input  type="text" value="{{old('local_ip_router')}}" id="local_ip_router" name="local_ip_router" class="form-control {{ ($errors->has('local_ip_router') ? 'is-invalid': '') }}">
                                            @if($errors->has('local_ip_router'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('local_ip_router')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="local_ip_router" class=" form-control-label">Estabelecimento<i style="color:red">*</i></label>
                                            <input  type="text" value="{{old('local_ip_router')}}" id="local_ip_router" name="local_ip_router" class="form-control {{ ($errors->has('local_ip_router') ? 'is-invalid': '') }}">
                                            @if($errors->has('local_ip_router'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('local_ip_router')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary btn-sm">
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
    <script>
       $(function(){
         var controlEstablishment = $("input[name='selected_establishment']").val();
         $("#contact").mask("(99) 99999-9999");
         $("#establishment_code").multi({
            "enable_search": true,
            "search_placeholder": "Pesquisar...",
         });

            $(".non-selected-wrapper").on('click', '.item', function(){
                controlEstablishment++;
                $("input[name='selected_establishment']").val(controlEstablishment);
            });

            $(".selected-wrapper").on('click', '.selected', function(){
                controlEstablishment--;
                $("input[name='selected_establishment']").val((controlEstablishment == 0 ? '' : controlEstablishment));
            })

       });
    </script>
@endsection
