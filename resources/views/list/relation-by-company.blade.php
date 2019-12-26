@extends('master.master')

@section('content')

<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">

                @foreach ($dados as $key => $operadoras)

                    <div class="col-md-2 mb-3">
                        <h3>Operadora: {{ $key }}</h3>
                    </div>

                    @foreach ($operadoras as $key => $chamados)
                        <div class="col-md-2  mb-3">
                            <h4 style="margin-left: 4%">Status: {{ $key }}</h4>
                        </div>

                        @foreach ($chamados as $chamado)

                          @php
                            $subCaller = $chamado->subCallers()->where(['status' => 'open', 'type' => 2])->first();
                            if($subCaller == null)
                                continue;
                          @endphp

                          <div class="col-md-4">
                            <div class="card border border-primary">
                                <div class="card-header">
                                    <strong class="card-title">Filial <b>{{ $chamado->establishment()->first()->establishment_code }}</b></strong>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">Link: <b>{{ $chamado->link()->first()->type_link }}</b></p>
                                    <p class="card-text">Loja: <b>{{ $chamado->establishment()->first()->establishment_code }}</b></p>
                                    <p class="card-text">Protocolo: <b>{{ $subCaller->call_telecommunications_company_number ?? '' }}</b></p>
                                    <p class="card-text">Circuito: <b>{{ $chamado->link()->first()->link_identification ?? '' }}</b></p>
                                    <p class="card-text">Tipo de Problema: <b>{{ $chamado->typeProblem()->first()->id_problem_type == 1 ? 'Link Inoperante' : 'Perda de Pacotes' }}</b></p>
                                    <p class="card-text">Protocolo Aberto em: <b>{{ date('d/m/Y H:i:s', strtotime($subCaller->updated_at)) ?? '' }}</b></p>
                                    <p class="card-text">Prazo de Normalização: <b>{{ $subCaller->deadline ?? '' }}</b></p>
                                    <p class="card-text">Status da Loja: <b>{{ $subCaller->status_establishment == 1 ? 'Loja Offline' : 'Funcionando pela Redundância' }}</b></p>
                                </div>
                            </div>
                        </div>
                        @endforeach

                    @endforeach


                @endforeach




            </div>
        </div>
    </div>
</div>

@endsection
