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
                            <strong>Cadastro </strong> de Categoria de Problemas
                            <small style="color:red" class="text-right"><i>*</i> Campos Obrigatórios</small>
                        </div>
                        <div class="card-body card-block">
                            <form action="{{route('category-problem.store')}}" method="post" class="" autocomplete="off">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="description_category" class=" form-control-label">Descrição<i style="color:red">*</i></label>
                                            <input  type="description_category" id="description_category" name="description_category" value="{{old('description_category')}}" class="form-control {{ ($errors->has('description_category') ? 'is-invalid': '') }}"">
                                            @if($errors->has('description_category'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('description_category')}}
                                                @endcomponent
                                            @endif
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
