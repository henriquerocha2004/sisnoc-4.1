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
                            <strong>Editar </strong> Link
                            <small style="color:red" class="text-right"><i>*</i> Campos Obrigatórios</small>
                        </div>
                        <div class="card-body card-block">
                            <form action="{{route('links.update', $link->id)}}" method="post" class="" autocomplete="off">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="name" class=" form-control-label">Tipo de link<i style="color:red">*</i></label>
                                            <select  name="type_link" id="type_link" class="form-control {{ ($errors->has('type_link') ? 'is-invalid': '') }}">
                                                <option value="">Selecione</option>
                                                @foreach ($typeLink as $desc)
                                                    <option value="{{$desc}}" {{(old('type_link') == $desc ? 'selected' : ($link->type_link == $desc ? 'selected' : ''))}} >{{$desc}}</option>
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
                                            <label for="contact" class=" form-control-label">Identificação<i style="color:red">*</i></label>
                                            <input  type="text" value="{{old('link_identification') ?? $link->link_identification}}" onkeyup="this.value = this.value.toUpperCase()" id="link_identification" name="link_identification"  class="form-control {{ ($errors->has('link_identification') ? 'is-invalid': '') }}">
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
                                            <input  type="text" onkeyup="this.value = this.value.toUpperCase()" placeholder="Ex.: 1MB, 10GB ..." value="{{old('bandwidth') ?? $link->bandwidth}}" id="bandwidth" name="bandwidth" class="form-control {{ ($errors->has('bandwidth') ? 'is-invalid': '') }}">
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
                                            <input  type="text" value="{{old('telecommunications_company')  ?? $link->telecommunications_company}}" id="telecommunications_company" name="telecommunications_company" class="form-control {{ ($errors->has('telecommunications_company') ? 'is-invalid': '') }}">
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
                                            <input  type="text" placeholder="Formato IP v.4" value="{{old('monitoring_ip') ?? $link->monitoring_ip}}" id="monitoring_ip" name="monitoring_ip" class="form-control {{ ($errors->has('monitoring_ip') ? 'is-invalid': '') }}">
                                            @if($errors->has('monitoring_ip'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('monitoring_ip')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="installed_router_model" class=" form-control-label">Modelo do Roteador</label>
                                            <input  type="text" value="{{old('installed_router_model') ?? $link->installed_router_model}}" id="installed_router_model" name="installed_router_model" class="form-control {{ ($errors->has('installed_router_model') ? 'is-invalid': '') }}">
                                            @if($errors->has('installed_router_model'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('installed_router_model')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="serial_router" class=" form-control-label">Número de Série Roteador</label>
                                            <input  type="text" value="{{old('serial_router') ?? $link->serial_router}}" id="serial_router" name="serial_router" class="form-control {{ ($errors->has('serial_router') ? 'is-invalid': '') }}">
                                            @if($errors->has('serial_router'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('serial_router')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="local_ip_router" class=" form-control-label">Ip Lan Roteador<i style="color:red">*</i></label>
                                            <input  type="text" placeholder="Formato IP v.4" value="{{old('local_ip_router') ?? $link->local_ip_router}}" id="local_ip_router" name="local_ip_router" class="form-control {{ ($errors->has('local_ip_router') ? 'is-invalid': '') }}">
                                            @if($errors->has('local_ip_router'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('local_ip_router')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="status" class=" form-control-label">Status<i style="color:red">*</i></label>
                                           <select  name="status" id="status" class="form-control {{ ($errors->has('status') ? 'is-invalid': '') }}">
                                                    <option value="">Selecione</option>
                                                    <option value="active" {{(old('status') == 'active' ? 'selected' : ($link->status == 'Ativo' ? 'selected' : ''))}} >Ativado</option>
                                                    <option value="inactive" {{(old('status') == 'inactive' ? 'selected' : ($link->status == 'Ativo' ? 'selected' : ''))}} >Desativado</option>
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
                                            <label for="establishment_id" class=" form-control-label">Estabelecimento<i style="color:red">*</i></label>
                                            <select style="width: 75%"  name="establishment_id" id="establishment_id" class="form-control multiple-select {{ ($errors->has('establishment_id') ? 'is-invalid': '') }}">
                                                <option value="">Selecione</option>
                                                @foreach ($establishments as $establishment)
                                                    <option value="{{$establishment->id}}" {{(old('establishment_id') == $establishment->id ? 'selected' : ($link->establishment_id == $establishment->id ? 'selected' : ''))}} >{{$establishment->establishment_code}}</option>
                                                @endforeach
                                            </select>
                                            @if($errors->has('establishment_id'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('establishment_id')}}
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
            $(".multiple-select").select2({
                width: 'resolve'
            });
       });
    </script>
@endsection
