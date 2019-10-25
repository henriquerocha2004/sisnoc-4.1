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
                            Resultados para <strong> {{ $term }} </strong>
                        </div>
                        <div class="card-body card-block">
                            @if(!empty($calleds))
                                <p class="mb-2">Chamados Encontrados:</p>

                                <div class="row">
                                    @foreach ($calleds as $called)
                                        <div class="col-md-4">
                                            <div class="card">
                                                <div class="card-header">
                                                    <strong class="card-title">{{ $called->caller_number }}
                                                        <small>
                                                            <span class="badge badge-{{ $called->status == 1 ? 'danger' : 'success' }} float-right mt-1">
                                                                {{ $called->status == 1 ? 'Fechado' : 'Aberto' }} </span>
                                                        </small>
                                                    </strong>
                                                </div>
                                                <div class="card-body">
                                                    <p class="card-text mb-2">
                                                        Estab.: {{ $called->establishment()->first()->establishment_code }} -
                                                        Link : {{ $called->link()->first()->type_link }}
                                                    </p>
                                                    <a class="btn btn-primary btn-sm pull-right" href="{{ route('called.edit', [$called->id, $called->subCallers()->first()->id]) }}">Visualizar</a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            @if(!empty($establishments))
                                <p class="mb-2">Estabelecimentos encontrados:</p>
                                <div class="row">
                                        @foreach ($establishments as $establishment)
                                            <div class="col-md-4">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <strong class="card-title">{{ $establishment->establishment_code }}
                                                            <small>
                                                                <span class="badge badge-{{ $establishment->establishment_status == 'close' ? 'danger' : 'success' }} float-right mt-1">
                                                                    {{ $establishment->establishment_status == 'close' ? 'Fechado' : 'Aberto' }} </span>
                                                            </small>
                                                        </strong>
                                                    </div>
                                                    <div class="card-body">
                                                        <p class="card-text mb-2">
                                                            Cidade: {{ $establishment->city }} -
                                                            UF : {{ $establishment->state }}
                                                        </p>
                                                        <a class="btn btn-primary btn-sm pull-right" href="{{ route('estabilishment.show', $establishment->id) }}">Visualizar</a>
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
