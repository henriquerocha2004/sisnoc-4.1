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
                            <strong>Editar </strong> Ação Tomada
                            <small style="color:red" class="text-right"><i>*</i> Campos Obrigatórios</small>
                        </div>
                        <div class="card-body card-block">
                            <form action="{{route('action-take.update', $actionTake->id)}}" method="post" class="" autocomplete="off">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="action_description" class=" form-control-label">Descrição<i style="color:red">*</i></label>
                                            <input  type="action_description" id="action_description" name="action_description" value="{{old('action_description') ?? $actionTake->action_description}}" class="form-control {{ ($errors->has('action_description') ? 'is-invalid': '') }}"">
                                            @if($errors->has('action_description'))
                                                @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                                    {{$errors->first('action_description')}}
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
