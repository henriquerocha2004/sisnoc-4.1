@extends('master.master')

@section('title')
    <title>Sisnoc | Editar Gerente Regional</title>
@endsection


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
                            <strong>Editar </strong>  Gerente Regional
                            <small style="color:red" class="text-right"><i>*</i> Campos Obrigatórios</small>
                        </div>
                        <div class="card-body card-block">
                            <form action="{{route('regionalManager.update', $regionalManager->id)}}" method="post" class="" autocomplete="off">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="name" class=" form-control-label">Nome<i style="color:red">*</i></label>
                                            <input  type="text" id="name" name="name" value="{{old('name') ?? $regionalManager->name}}" class="form-control {{ ($errors->has('name') ? 'is-invalid': '') }}"">
                                            @if($errors->has('name'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('name')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="contact" class=" form-control-label">Contato<i style="color:red">*</i></label>
                                        <input  type="text" value="{{old('contact') ?? $regionalManager->contact}}" placeholder="(xx) xxxxx-xxxx" id="contact" name="contact"  class="form-control {{ ($errors->has('contact') ? 'is-invalid': '') }}">
                                            @if($errors->has('contact'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('contact')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="email" class=" form-control-label">E-mail<i style="color:red">*</i></label>
                                            <input  type="email" value="{{old('email') ?? $regionalManager->email}}" id="email" name="email" class="form-control {{ ($errors->has('email') ? 'is-invalid': '') }}">
                                            @if($errors->has('email'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('email')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mt-4">
                                        <div class="row">
                                            <h3 class="title-5 col-md-6">Estabelecimentos associados:</h3>
                                        </div>
                                        <input type="hidden" name="selected_establishment" value="{{old('selected_establishment') ?? count($regionalManager->idEstablishments()['ids'])}}">
                                        <div class="row">
                                            <div class="col col-md-9 mt-2">
                                                <select name="establishment_code[]" id="establishment_code" multiple="" class="form-control {{ ($errors->has('selected_establishment') ? 'is-invalid': '') }}">
                                                    @foreach ($establishments as $establishment)
                                                        @if (old('establishment_code') || $regionalManager->establishments()->get())
                                                            @foreach (old('establishment_code') ?? $regionalManager->idEstablishments()['ids'] as $code)
                                                                @php
                                                                   $selected = ($establishment->id == $code ? 'selected' : '');
                                                                   if($selected == 'selected')
                                                                        break;
                                                                @endphp
                                                            @endforeach
                                                            <option value="{{$establishment->id}}" {{$selected}}>{{$establishment->establishment_code}} - {{$establishment->state}}</option>
                                                        @else
                                                            <option value="{{$establishment->id}}" >{{$establishment->establishment_code}} - {{$establishment->state}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                 @if($errors->has('selected_establishment'))
                                                    @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                        {{$errors->first('selected_establishment')}}
                                                    @endcomponent
                                                @endif
                                            </div>
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
