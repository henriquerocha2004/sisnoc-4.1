 @extends('master.master')


 @section('content')
 <!-- MAIN CONTENT-->
 <div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                @if (session('alert'))
                    @component('compoments.message', ['type' => session('alert')['messageType']])
                        {{session('alert')['message']}}
                    @endcomponent
                @endif
                <div id="totais-gerais" class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1">Dashboard - Totais Gerais</h2>
                    </div>
                </div>
            </div>
            <div class="row m-t-25">
                <div class="col-md-3 col-lg-3">
                    <div class="statistic__item">
                        <h2 class="number">{{ $dashboard['qtd_active_establishment'] }}</h2>
                        <span class="desc">Lojas Ativas</span>
                        <div class="icon">
                            <i class="zmdi zmdi-home"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-lg-3">
                    <div class="statistic__item">
                        <h2 class="number">{{ $dashboard['qtd_open_called'] }}</h2>
                        <span class="desc">Chamados Abertos</span>
                        <div class="icon">
                            <i class="zmdi zmdi-file"></i>
                        </div>
                    </div>
                </div>
                @foreach ($dashboard['qtd_links_active'] as $key => $link)
                    <div class="col-md-3 col-lg-3">
                        <div class="statistic__item">
                            <h2 class="number">{{ $link }}</h2>
                            <span class="desc">Total de {{ $key }}</span>
                            <div class="icon">
                                <i class="zmdi zmdi-link"></i>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="row">
                <div id="chamados-abertos-link" class="col-md-12 mb-3">
                    <div class="overview-wrap">
                        <h2 class="title-1">Chamados Abertos por Link</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($dashboard['qtd_open_called_by_link'] as $key => $link)
                    @if($key == '4G')
                        @php continue; @endphp
                    @endif
                    <div class="col-md-3 col-lg-3">
                        <div class="statistic__item">
                            <h2 class="number">{{ $link }}</h2>
                            <span class="desc">{{ $key }}</span>
                            <div class="icon">
                                <i class="zmdi zmdi-file"></i>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="row" id="chamados-current">
                @if(count($dashboard['called_open_current_date']) >= 1)
                    <div  class="col-md-6" >
                        <h2 class="title-1 m-b-25">Chamados Abertos Hoje</h2>
                        <div class="table-responsive table--no-card m-b-40" style="overflow: scroll; height: 40vh ">
                            <table class="table table-borderless table-striped table-earning">
                                <thead>
                                    <tr>
                                        <th>Número</th>
                                        <th>Aberto por:</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dashboard['called_open_current_date'] as $called)
                                        <tr>
                                            <td><a href="{{ route('called.edit', [$called->id, $called->subCallers()->first()->id]) }}">{{ $called->caller_number }}</a></td>
                                            <td>{{ $called->userOpen()->first()->name }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
                @if(count($dashboard['called_closed_current_date']) >= 1)
                    <div class="col-md-6" >
                        <h2 class="title-1 m-b-25">Chamados Fechados Hoje</h2>
                        <div class="table-responsive table--no-card m-b-40" style="overflow: scroll; height: 40vh ">
                            <table class="table table-borderless table-striped table-earning">
                                <thead>
                                    <tr>
                                        <th>Número</th>
                                        <th>Fechado por:</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dashboard['called_closed_current_date'] as $called)
                                        <tr>
                                            <td><a href="{{ route('called.edit', [$called->id, $called->subCallers()->first()->id]) }}">{{ $called->caller_number }}</a></td>
                                            <td>{{ $called->userClose()->first()->name }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>

            <div class="row">
                @if(count($dashboard['my_callers']) >= 1)
                    <div class="col-md-12">
                        <h2 class="title-1 m-b-25">Meus Chamados</h2>
                    </div>
                    <div class="col-md-3 col-lg-3">
                        <div class="statistic__item">
                            <h2 class="number">{{ count($dashboard['my_callers']) }}</h2>
                            <span class="desc">Chamado(s) Abertos por mim</span>
                            <div class="icon">
                                <i class="zmdi zmdi-file"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9" >
                        <div class="table-responsive table--no-card m-b-40" style="overflow: scroll; height: 40vh ">
                            <table class="table table-borderless table-striped table-earning">
                                <thead>
                                    <tr>
                                        <th>Número</th>
                                        <th>Link</th>
                                        <th>Aberto Em</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dashboard['my_callers'] as $called)
                                        <tr>
                                            <td><a href="{{ route('called.edit', [$called->id, $called->subCallers()->first()->id]) }}">{{$called->caller_number }}</a></td>
                                            <td>{{ $called->link()->first()->type_link }}</td>
                                            <td>{{ $called->created_at }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>

            <div class="row">
                @foreach ($dashboard['called_open_by_responsability'] as $key => $item)

                  <div id="{{ $key }}"></div>

                  <div  class="col-md-10">
                        @if($key == 'Operadora' || $key == 'Técnico Local' || $key == 'SEMEP')
                           <h2 class="title-1 m-b-25">Solicitações feitas a(o) {{ $key }} </h2>
                        @elseif($key == 'Inadiplência' || $key == 'Falta de Energia')
                           <h2 class="title-1 m-b-25"> Chamados Por {{ $key }} </h2>
                        @endif
                  </div>
                  @if($key == 'Operadora')
                        <div class="row">
                            <div class="col-md-3">
                                <button class="btn btn-success" data-toggle="modal" data-target="#modal-sheet" >Gerar Planilha</button>
                            </div>
                        </div>
                  @elseif($key == 'Técnico Local' || $key == 'SEMEP')
                    <div class="row">
                        <div class="col-md-3">
                            <form action="{{ ($key == 'Técnico Local' ? route('reports.callersOtrs') : route('reports.semep') ) }}" method="POST">
                                @csrf
                                <button class="btn btn-success" >Gerar Planilha</button>
                            </form>
                        </div>
                    </div>
                  @endif

                  <div class="col-md-3 col-lg-3">
                        <div class="statistic__item">
                            <h2 class="number">{{ $item['total'] }}</h2>
                            @if($key == 'Operadora' || $key == 'Técnico Local' || $key == 'SEMEP')
                                <span class="desc">Solicitações abertas</span>
                            @elseif($key == 'Inadiplência' || $key == 'Falta de Energia')
                                <span class="desc">Chamados Abertos</span>
                            @endif

                            <div class="icon">
                                <i class="zmdi zmdi-file"></i>
                            </div>
                        </div>
                   </div>
                   <div class="col-md-9" >
                        <div class="table-responsive table--no-card m-b-40" style="overflow: scroll; height: 40vh ">
                            <table class="table table-borderless table-striped table-earning">
                                <thead>
                                    <tr>
                                        <th>Número</th>
                                        <th>Link</th>
                                        <th> Chamado {{ ($key == 'Operadora' ? 'Operadora' : ($key == 'Técnico Local' ? 'OTRS' : ($key == 'SEMEP' ? 'SISMAN' : ''))) }}</th>
                                        @if($key == 'Operadora')
                                            <th>Prazo de Normalização</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($item['callers'] as $subCalled)
                                        <tr>
                                            <td><a href="{{ route('called.edit', [$subCalled->called()->first()->id, $subCalled->id]) }}">{{ $subCalled->called()->first()->caller_number }}</a></td>
                                            <td>{{ $subCalled->called()->first()->link()->first()->type_link }}</td>
                                            <td>  {{ ($key == 'Operadora' ? $subCalled->call_telecommunications_company_number : ($key == 'Técnico Local' ? $subCalled->otrs : ($key == 'SEMEP' ? $subCalled->sisman : ''))) }}</td>
                                            @if($key == 'Operadora')
                                                <td>{{ $subCalled->deadline }}</td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                @endforeach
            </div>


            @include('footer.footer')
        </div>
    </div>
</div>

<div class="modal fade" id="modal-sheet" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">O que deseja Gerar ?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('reports.callersTeleCompany') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="start" class=" form-control-label">Informe o link: <i style="color:red">*</i></label>
                                <select name="link" class="form-control">
                                    <option value="">Selecione</option>
                                    @foreach ( $dashboard['typeLinks'] as $type)
                                        <option value="{{ $type }}">{{ $type }}</option>
                                    @endforeach
                                    <option value="ALL">Todos</option>
                                </select>
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



<!-- END MAIN CONTENT-->
@endsection
