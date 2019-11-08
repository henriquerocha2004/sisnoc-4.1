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
                                <strong>Editar </strong> Estabelecimento
                                <small style="color:red" class="text-right"><i>*</i> Campos Obrigatórios</small>
                            </div>
                            <div class="card-body card-block">
                                <form action="{{route('estabilishment.update', $establishment->id)}}" method="post" class="" autocomplete="off">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="id" value="{{$establishment->id}}">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="establishment_code" class=" form-control-label">Código Estabelecimento<i style="color:red">*</i></label>
                                                <input  type="text" id="establishment_code" name="establishment_code" value="{{old('establishment_code') ?? $establishment->establishment_code}}" class="form-control {{ ($errors->has('establishment_code') ? 'is-invalid': '') }}"">
                                                @if($errors->has('establishment_code'))
                                                   @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                        {{$errors->first('establishment_code')}}
                                                   @endcomponent
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="status" class=" form-control-label">Status<i style="color:red">*</i></label>
                                                <select  name="establishment_status" id="status" class="form-control {{ ($errors->has('establishment_status') ? 'is-invalid': '') }}"">
                                                    <option value="">Selecione</option>
                                                    <option value="open" {{(old('establishment_status') == 'open' ? 'selected' : ($establishment->establishment_status == 'open' ? 'selected' : ''))}}>Aberto</option>
                                                    <option value="close" {{(old('establishment_status') == 'close' ? 'selected' : ($establishment->establishment_status == 'close' ? 'selected' : ''))}}>Fechado</option>
                                                </select>
                                                @if($errors->has('establishment_status'))
                                                    @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                        {{$errors->first('establishment_status')}}
                                                    @endcomponent
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="opening_hours" class=" form-control-label">Horário de Funcionamento</label>
                                                <input type="text" id="opening_hours" value="{{old('opening_hours') ?? $establishment->opening_hours}}" name="opening_hours"  class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="address" class=" form-control-label">Endereço<i style="color:red">*</i></label>
                                                <input  type="text" value="{{old('address') ?? $establishment->address}}" id="address" name="address" class="form-control {{ ($errors->has('address') ? 'is-invalid': '') }}">
                                                @if($errors->has('address'))
                                                    @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                        {{$errors->first('address')}}
                                                    @endcomponent
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="neighborhood" class=" form-control-label">Bairro<i style="color:red">*</i></label>
                                                <input  type="text" value="{{old('neighborhood') ?? $establishment->neighborhood}}" id="neighborhood" name="neighborhood"  class="form-control {{ ($errors->has('neighborhood') ? 'is-invalid': '') }}">
                                                @if($errors->has('neighborhood'))
                                                    @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                        {{$errors->first('neighborhood')}}
                                                    @endcomponent
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="city" class=" form-control-label">Cidade<i style="color:red">*</i></label>
                                                <input  type="text" value="{{old('city') ?? $establishment->city}}" id="city" name="city"  class="form-control {{ ($errors->has('city') ? 'is-invalid': '') }}">
                                                @if($errors->has('city'))
                                                    @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                        {{$errors->first('city')}}
                                                    @endcomponent
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="state" class=" form-control-label">UF<i style="color:red">*</i></label>
                                                <input  type="text" value="{{old('state') ?? $establishment->state}}" id="state" name="state" maxlength="2" onkeyup="this.value = this.value.toUpperCase()" placeholder="Sigla, Ex: SP,RJ,BA" class="form-control {{ ($errors->has('state') ? 'is-invalid': '') }}">
                                                @if($errors->has('state'))
                                                    @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                        {{$errors->first('state')}}
                                                    @endcomponent
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="document_establishment" class=" form-control-label">CNPJ</label>
                                                <input type="text" value="{{old('document_establishment') ?? $establishment->document_establishment}}" id="document_establishment" name="document_establishment" placeholder="xx.xxx.xxx/xxxx-xx" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="document_establishment_alternate" class=" form-control-label">Inscrição Estadual</label>
                                                <input type="text" value="{{old('document_establishment_alternate') ?? $establishment->document_establishment_alternate}}" id="document_establishment_alternate" name="document_establishment_alternate"  class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="phone_establishment" class=" form-control-label">Telefone</label>
                                            <input type="text" value="{{old('phone_establishment') ?? $establishment->phone_establishment}}" id="phone_establishment" name="phone_establishment" placeholder="(xx) xxxx-xxxx"  class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="manager_name" class=" form-control-label">Gerente<i style="color:red">*</i></label>
                                                <input  type="text" value="{{old('manager_name') ?? $establishment->manager_name}}" id="manager_name" name="manager_name"  class="form-control {{ ($errors->has('manager_name') ? 'is-invalid': '') }}">
                                                @if($errors->has('manager_name'))
                                                    @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                        {{$errors->first('manager_name')}}
                                                    @endcomponent
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="manager_contact" class=" form-control-label">Contato Gerente<i style="color:red">*</i></label>
                                            <input  type="text" value="{{old('manager_contact') ?? $establishment->manager_contact}}" placeholder="(xx) xxxxx-xxxx" id="manager_contact" name="manager_contact"  class="form-control {{ ($errors->has('manager_contact') ? 'is-invalid': '') }}">
                                                @if($errors->has('manager_contact'))
                                                    @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                        {{$errors->first('manager_contact')}}
                                                    @endcomponent
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="branch_establishment" class=" form-control-label">Ramal</label>
                                            <input type="text" value="{{old('branch_establishment') ?? $establishment->branch_establishment}}" id="branch_establishment" name="branch_establishment"  class="form-control phone">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="regional_manager_code" class=" form-control-label">Gerente Regional<i style="color:red">*</i></label>
                                                <select  name="regional_manager_code" id="regional_manager_code" class="form-control {{ ($errors->has('regional_manager_code') ? 'is-invalid': '') }}">
                                                    <option value="">Selecione</option>
                                                    @foreach ($regionalManagers as $regionalManager)
                                                        <option value="{{$regionalManager->id}}" {{(old('regional_manager_code') == $regionalManager->id ? 'selected' : ($establishment->regionalManager()->first()->id == $regionalManager->id ? 'selected' : ''))}}>{{$regionalManager->name}}</option>
                                                    @endforeach
                                                </select>
                                                @if($errors->has('regional_manager_code'))
                                                    @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                        {{$errors->first('regional_manager_code')}}
                                                    @endcomponent
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="technician_code" class=" form-control-label">Responsável Técnico<i style="color:red">*</i></label>
                                                <select  name="technician_code" id="technician_code" class="form-control {{ ($errors->has('technician_code') ? 'is-invalid': '') }}">
                                                    <option value="">Selecione</option>

                                                    @foreach ($technicalManagers as $technicalManager)
                                                        <option value="{{$technicalManager->id}}" {{(old('technician_code') == $technicalManager->id ? 'selected' : ($establishment->technicalManager()->first()->id == $technicalManager->id ? 'selected' : ''))}} >{{$technicalManager->name}}</option>
                                                    @endforeach
                                                </select>
                                                @if($errors->has('technician_code'))
                                                    @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                        {{$errors->first('technician_code')}}
                                                    @endcomponent
                                                @endif
                                            </div>
                                        </div>

                                    </div>


                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fa fa-dot-circle-o"></i> Atualizar
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
        $("#document_establishment").mask("99.999.999/9999-99");
        $("#phone_establishment").mask("(99) 9999-9999");
        $("#manager_contact").mask("(99) 99999-9999");
        var statusStart =  $('#status').val();

        $('#status').change(function(){

            var status = $(this).val();

            if(status == 'close'){
                $.confirm({
                    title: 'Aviso Sisnoc',
                    content : 'Atenção! Ao selecionar fechado e salvar, além de alterar o cadastro deste estabelecimento, você irá fechar todos os chamados abertos e irá desativar os links associados. Confirma?',
                    buttons:{
                        SIM: function(){

                        },
                        Não: function() {
                            $('#status').val(statusStart);
                        }
                    }
                })
            }
        });

       });
    </script>
@endsection
