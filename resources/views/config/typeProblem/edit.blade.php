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
                            <strong>Editar </strong> Tipo de Problema
                            <small style="color:red" class="text-right"><i>*</i> Campos Obrigatórios</small>
                        </div>
                        <div class="card-body card-block">
                            <form action="{{route('type-problem.update', $typeProblem->id)}}" method="post" class="" autocomplete="off">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="problem_description" class=" form-control-label">Descrição<i style="color:red">*</i></label>
                                            <input  type="problem_description" id="problem_description" name="problem_description" value="{{old('problem_description') ?? $typeProblem->problem_description}}" class="form-control {{ ($errors->has('description_cause') ? 'is-invalid': '') }}"">
                                            @if($errors->has('problem_description'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('problem_description')}}
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
