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
                           <strong>Relatórios</strong>
                        </div>
                        <div class="card-body card-block">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <strong class="card-title">Disponibilidade e Interrupções
                                                <small>
                                                    <span class="badge badge-success float-right mt-1">
                                                        Excel </span>
                                                </small>
                                            </strong>
                                        </div>
                                        <div class="card-body">
                                            <button class="btn btn-primary btn-sm pull-right" id="btn-disponibility" data-toggle="modal" data-target="#modal-data">Gerar</button>
                                        </div>
                                    </div>
                                </div>
                                {{--  <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <strong class="card-title">Relação de Links
                                                <small>
                                                    <span class="badge badge-success float-right mt-1">
                                                        Excel </span>
                                                </small>
                                            </strong>
                                        </div>
                                        <div class="card-body">
                                            <a class="btn btn-primary btn-sm pull-right" href="{{ route('reports.links') }}">Gerar</a>
                                        </div>
                                    </div>
                                </div>  --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-data" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mediumModalLabel">Informe o Período</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('reports.disponibility') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="start" class=" form-control-label">Data Inicial<i style="color:red">*</i></label>
                                    <input required readonly  type="text" id="start" name="start"  class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="end" class="form-control-label">Data Final<i style="color:red">*</i></label>
                                    <input required readonly  type="text" id="end" name="end"  class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button class="btn btn-primary">Confirmar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{url('/l10n/pt.js')}}"></script>
    <script>
        $("#btn-disponibility").click(function(){

        });

        $("#start, #end").flatpickr({
            enableTime:false,
            dateFormat: "d/m/Y",
            locale: "pt"
        });
    </script>
@endsection
