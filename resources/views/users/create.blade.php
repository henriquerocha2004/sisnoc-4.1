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
                            <strong>Cadastro </strong> de Usuário
                            <small style="color:red" class="text-right"><i>*</i> Campos Obrigatórios</small>
                        </div>
                        <div class="card-body card-block">
                            <form action="{{route('users.store')}}" method="post" class="" autocomplete="off">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="name" class=" form-control-label">Nome<i style="color:red">*</i></label>
                                            <input  type="text" id="name" name="name" value="{{old('name')}}" class="form-control {{ ($errors->has('name') ? 'is-invalid': '') }}"">
                                            @if($errors->has('name'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('name')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="email" class=" form-control-label">Login<i style="color:red">*</i></label>
                                        <input  type="text" value="{{old('email')}}" id="email" name="email"  class="form-control {{ ($errors->has('email') ? 'is-invalid': '') }}">
                                            @if($errors->has('email'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('email')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="password" class=" form-control-label">Senha<i style="color:red">*</i></label>
                                        <input  type="password" value="{{old('password')}}" id="password" name="password"  class="form-control {{ ($errors->has('password') ? 'is-invalid': '') }}">
                                            @if($errors->has('password'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('password')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="password_rep" class=" form-control-label">Repita a senha<i style="color:red">*</i></label>
                                        <input  type="password" value="{{old('password_rep')}}" id="password_rep" name="password_rep"  class="form-control {{ ($errors->has('password_rep') ? 'is-invalid': '') }}">
                                            @if($errors->has('password_rep'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('password_rep')}}
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
