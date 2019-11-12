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
                            <strong>Editar </strong>  Causa de Problema
                            <small style="color:red" class="text-right"><i>*</i> Campos Obrigatórios</small>
                        </div>
                        <div class="card-body card-block">
                            <form action="{{route('cause-problem.update', $causeProblem->id)}}" method="post" class="" autocomplete="off">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="description_cause" class=" form-control-label">Descrição<i style="color:red">*</i></label>
                                            <input  type="description_cause" id="description_cause" name="description_cause" value="{{old('description_cause') ?? $causeProblem->description_cause}}" class="form-control {{ ($errors->has('description_cause') ? 'is-invalid': '') }}"">
                                            @if($errors->has('description_cause'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('description_cause')}}
                                                @endcomponent
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="id_category" class=" form-control-label">Categoria<i style="color:red">*</i></label>
                                            <select required  name="id_category" id="id_category" class="form-control {{ ($errors->has('id_category') ? 'is-invalid': '') }}">
                                                <option value="">Selecione ...</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}" {{ ($category->id == $causeProblem->id_category ? 'selected' : '') }}>{{ $category->description_category }}</option>
                                                @endforeach
                                            </select>
                                            @if($errors->has('id_category'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('id_category')}}
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
