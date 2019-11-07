@extends('master.master')

@section('content')

<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            @if (session('alert'))
                @component('compoments.message', ['type' => session('alert')['messageType']])
                    {{session('alert')['message']}}
                @endcomponent
            @endif
            <div class="card">
                <div class="card-header">
                    <strong>Configurações </strong> do Sistema
                </div>
            <form action="{{ route('config.update') }}" method="POST">
                @csrf
                <div class="card-body card-block">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="btn-group">
                                    <a href="{{ route('category-problem.index') }}" class="btn btn-primary">Ger. Categ. Problemas</a>
                                    <a href="{{ route('cause-problem.index') }}" class="btn btn-primary">Ger. Causa de Problemas</a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="btn-group">
                                    <a href="{{ route('type-problem.index') }}" class="btn btn-primary">Ger. Tipo de Problema</a>
                                    <a href="{{ route('action-take.index') }}" class="btn btn-primary">Ger. Ação Tomada</a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mt-3">
                                <div class="btn-group">
                                    <a href="{{ route('users.index') }}" class="btn btn-primary">Ger. de Usuários</a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-7 mt-3">
                                <div class="form-group">
                                    <label for="opening_hours" class=" form-control-label">Path do Terminal Web</label>
                                    <input type="text" id="path_web_terminal" value="{{ $config->path_web_terminal }}" name="path_web_terminal"  class="form-control">
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

@endsection
