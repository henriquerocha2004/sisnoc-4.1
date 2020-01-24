@extends('master.master')

@section('title')
    <title>Sisnoc | Resultados de busca para {{ $term }}</title>
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
                            Resultados para <strong> {{ $term }} </strong>
                        </div>
                        <div class="card-body card-block">
                            @if(!empty($establishments))
                                <p class="mb-2"><b>Estabelecimentos encontrados:</b></p>
                                <div class="row">
                                        @foreach ($establishments as $establishment)
                                            <div class="col-md-4">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <strong class="card-title">{{ $establishment->establishment_code }}
                                                            <small>

                                                                <span class="badge badge-{{ $establishment->establishment_status == 'close' ? 'danger' : 'success' }} float-right mt-1">
                                                                    {{ $establishment->establishment_status == 'close' ? 'Encerrou Atividades' : 'Ativa' }} </span>
                                                            </small>
                                                            @if($establishment->holyday == date('Y-m-d'))
                                                                <small>
                                                                    <span class="badge badge-danger float-right mt-1">
                                                                        Feriado Local/Nacional </span>
                                                                </small>
                                                            @endif

                                                            @if($establishment->energy_fault == 1)
                                                                <small>
                                                                    <span class="badge badge-danger float-right mt-1">
                                                                        Loja Sem energia</span>
                                                                </small>
                                                            @endif
                                                        </strong>
                                                    </div>
                                                    <div class="card-body">
                                                        <p class="card-text">
                                                            Cidade: {{ $establishment->city }}
                                                        </p>
                                                        <p>
                                                            Bairro: {{$establishment->neighborhood}}
                                                        </p>
                                                        <p>
                                                            UF : {{ $establishment->state }}
                                                        </p>
                                                        <a class="btn btn-primary btn-sm pull-right" href="{{ route('estabilishment.show', $establishment->id) }}">Visualizar</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                @if(!empty($calleds))
                                    <hr>
                                    <p class="mb-2"><b>Chamados Encontrados:</b></p>

                                    <div class="row">
                                        @foreach ($calleds as $called)
                                            <div class="col-md-4">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <strong class="card-title">{{ $called->caller_number }}
                                                            <small>
                                                            <span class="badge badge-{{ $called->status == 1 ? 'danger' : 'warning' }} float-right mt-1">
                                                                {{ $called->status == 1 ? 'Fechado' : 'Aberto' }} </span>
                                                            </small>
                                                        </strong>
                                                    </div>
                                                    <div class="card-body">
                                                        <p class="card-text mb-2">
                                                            Estab.: {{ $called->establishment()->first()->establishment_code }}
                                                        </p>
                                                        <p class="card-text mb-2">
                                                            Link : {{ $called->link()->first()->type_link }}
                                                        </p>
                                                        <a class="btn btn-primary btn-sm pull-right" href="{{ route('called.edit', [$called->id, $called->subCallers()->first()->id]) }}">Visualizar</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
