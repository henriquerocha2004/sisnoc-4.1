@extends('master.master')

@section('title')
    <title>Sisnoc | Dados da Loja {{ $establishment->establishment_code }}</title>
@endsection

@section('content')

@section('css')
    <style>
        .table-responsive{margin-left: 3%}
        .table-data3 thead tr th{padding: 1%;font-size: 0.8em;}
        .table-data3 tbody tr td{padding: 1%;font-size: 0.8em;}
    </style>
@endsection

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
                        <div class="card-header" style="background-color: {{($establishment->establishment_status == 'open' ? ($establishment->holyday == date('Y-m-d') ? 'lightsalmon' : 'darkseagreen') : 'lightsalmon')}}">
                            <div class="row">
                                <div class="col-md-6">
                                    Informações da <strong>
                                        @can('manager-establishment-regionalManager-links-caller-create-reports')
                                            <a style="color: #666; text-decoration: underline" href="{{route('estabilishment.edit', [$establishment->id])}}">{{ $establishment->establishment_code }}</a>
                                        @else
                                            {{ $establishment->establishment_code }}
                                        @endcan

                                        {{$establishment->holyday == date('Y-m-d') ? " - Feriado Local" : ($establishment->establishment_status == 'close' ? ' - Estabelecimento Fechado' : '')}}</strong>
                                </div>
                                @can('manager-establishment-regionalManager-links-caller-create-reports')
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-sm btn-danger pull-right" data-toggle="modal" data-target="#modal-data">Nota</button>
                                        <button type="button" id="btn-holiday" class="btn btn-sm btn-danger pull-right">Informar Feriado</button>
                                    </div>
                                @endcan
                            </div>
                        </div>
                        <input type="hidden"  id="idEstabilishment" value="{{$establishment->id}}">
                        <div class="card-body card-block">
                            <div class="row">
                                @forelse ($notes as $note)
                                    @component('compoments.message', ['type' => 'warning'])
                                        <div class="col-md-12 ml-3">
                                            <p style="font-size: 0.8em;">{{ $note->user()->first()->name }}, disse: {{ $note->desc }}</p>
                                        </div>
                                    @endcomponent
                                @empty

                                @endforelse
                            </div>
                            <div class="row">
                                <div class="table-responsive col-md-11">
                                    <table class="table table-borderless table-data3">
                                        <thead>
                                            <tr>
                                                <th style="font-size: 0.8em">Endereço</th>
                                                <th>Bairro</th>
                                                <th>Cidade</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{$establishment->address}}</td>
                                                <td>{{$establishment->neighborhood}}</td>
                                                <td>{{$establishment->city}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="table-responsive col-md-11">
                                    <table class="table table-borderless table-data3">
                                        <thead>
                                            <tr>
                                                <th>UF</th>
                                                <th>CNPJ</th>
                                                <th>I.E</th>
                                                <th>Ramal</th>
                                                <th>Telefone Fixo</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{$establishment->state}}</td>
                                                <td>{{$establishment->document_establishment}}</td>
                                                <td>{{$establishment->document_establishment_alternate}}</td>
                                                <td>{{$establishment->branch_establishment}}</td>
                                                <td>{{$establishment->phone_establishment}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="table-responsive col-md-11">
                                    <table class="table table-borderless table-data3">
                                        <thead>
                                            <tr>
                                                <th>Situação</th>
                                                <th>Horário de Funcionamento</th>
                                                <th>Gerente</th>
                                                <th>Contato Gerente</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{$establishment->establishment_status}}</td>
                                                <td>{{$establishment->opening_hours}}</td>
                                                <td>{{$establishment->manager_name}}</td>
                                                <td>{{$establishment->manager_contact}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="table-responsive col-md-11">
                                    <table class="table table-borderless table-data3">
                                        <thead>
                                            <tr>
                                                <th>Gerente Regional</th>
                                                <th>Contato Gerente</th>
                                                <th>Resp. Técnico</th>
                                                <th>Contato Resp. Técnico</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{$establishment->regionalManager()->first()->name}}</td>
                                                <td>{{$establishment->regionalManager()->first()->contact}}</td>
                                                <td>{{$establishment->technicalManager()->first()->name}}</td>
                                                <td>{{$establishment->technicalManager()->first()->contact}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <h4 class="text-center m-4">Links do Estabelecimento</h4>
                                </div>
                                <div class="col-md-5">
                                    <button type="button" id="refresh-links"  class="btn btn-sm btn-primary pull-right mt-3" data-content="">Atualizar status</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive col-md-11">
                                        <table id="tblLinks" class="table table-borderless table-data3">
                                            <thead>
                                                <tr>
                                                    <th>Link</th>
                                                    <th>Circuito</th>
                                                    <th style="width: 8%">Banda</th>
                                                    <th>ISP</th>
                                                    <th>Router</th>
                                                    <th>Chassi</th>
                                                    <th>Ip Mon.</th>
                                                    <th>Ip local</th>
                                                    <th style="width: 8%">Ativo?</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($establishment->links()->get() as $link)
                                                <tr data-id-link="{{$link->id}}">
                                                    <td><a style="color: #666; text-decoration: underline" href="{{route('links.edit', [$link->id])}}">{{$link->type_link}}</a></td>
                                                    <td>{{$link->link_identification}}</td>
                                                    <td>{{$link->bandwidth}}</td>
                                                    <td>{{$link->telecommunications_company}}</td>
                                                    <td>{{$link->installed_router_model}}</td>
                                                    <td>{{$link->serial_router}}</td>
                                                    <td>{{$link->monitoring_ip}}</td>
                                                    <td>{{$link->local_ip_router}}</td>
                                                    <td>{{$link->status}}</td>
                                                    <td class="refresh" id="{{$link->id}}">
                                                        <i class="fa fa-refresh fa-spin"></i>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mt-3 mb-3 pt-3">
                                    <h4 class="text-center">Área de Testes</h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    @foreach ($establishment->links()->get() as $link)
                                        <button type="button" class="btn-type-link btn btn-sm btn-primary mb-1" data-ip-mon="{{$link->monitoring_ip}}">Conectar pelo {{$link->type_link}}</button>
                                    @endforeach
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8 mt-2 mb-2">
                                    <div class="ml-5">
                                        <label class="switch switch-text switch-primary switch-pill">
                                            <input type="checkbox" id="lg" class="switch-input" checked="true">
                                            <span data-on="On" data-off="Off" class="switch-label"></span>
                                            <span class="switch-handle"></span>
                                        </label>
                                        <small>Autenticar no router com as credênciais do AD</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" id="btn-terminal" class="btn btn-secondary pull-right d-none">Reiniciar Terminal</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12" >
                                    <div id="terminal-container" style="width: 87%">
                                        <iframe name="interno" style="width: 100%; background-color: black; height: 71vh" id="terminal" src="http://{{ env('TERMINAL_WEB_IP') }}:8000/terminal?ip={{$establishment->links()->first()->monitoring_ip}}&lg=d"></iframe>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mt-3 mb-3">
                                    <h4 class="text-center">Chamados gerados</h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-11">
                                    <div class="table-data__tool">
                                       @can('manager-establishment-regionalManager-links-caller-create-reports')
                                            <div class="table-data__tool-right">
                                                <a href="{{route('called.create')}}" class="au-btn au-btn-icon au-btn--green au-btn--small">
                                                    <i class="zmdi zmdi-plus"></i>Novo Chamado</a>
                                            </div>
                                        @endcan
                                    </div>
                                    <div class="table-responsive table-responsive-data2">
                                        <table class="table table-data2">
                                            <thead>
                                                <tr>
                                                    <th>Chamado</th>
                                                    <th>Link</th>
                                                    <th>Ultima Ação</th>
                                                    <th>Situação</th>
                                                    <th>Aberto Por</th>
                                                </tr>
                                            </thead>

                                        </table>
                                    </div>
                                </div>
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
                    <h5 class="modal-title" id="mediumModalLabel">Nota do estabelecimento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('establishment.note') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="desc"> Observações </label>
                                    <input type="hidden" name="id_establishment" value="{{ $establishment->id }}">
                                    <textarea name="desc" id="desc" cols="30" rows="5" class="form-control {{ ($errors->has('desc') ? 'is-invalid': '') }}">{{old('desc')}}</textarea>
                                    @if($errors->has('desc'))
                                        @component('compoments.feedbackInputs', ['typeFeed' => 'invalid'])
                                            {{$errors->first('desc')}}
                                        @endcomponent
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="validate" class="form-control-label">Validade<i style="color:red">*</i></label>
                                    <input required readonly  type="text" id="validate" name="validate"  class="form-control">
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
<script src="{{asset('js/datatables.js')}}"></script>
      <script>
          $(function(){
            $("#validate").flatpickr({
                minDate: "today",
                enableTime:false,
                dateFormat: "d/m/Y",
                locale: "pt"
            });

            //Evento que faz os testes de ping nos links do estabelecimento$("#idEstabilishment").val()
            $("#tblLinks > tbody > tr").each(function(i, v){
                checkStatusLink(v.getAttribute('data-id-link'));
            });

            //Evento que verifica se o serviço de terminal está ativo.
            $.get('{{url('check-service-terminal')}}', function(r){
                if(r.result == false){
                    $('#btn-terminal').removeClass('d-none');
                }
            });

            //Evento que reinicia o terminal
            $("#btn-terminal").click(function(){
                $(this).html("Aguarde ...");
                $.get('{{url('terminal')}}');
                setTimeout(function(){
                    window.location.reload()
                }, 3000);
            });
          });

          //Evento que conecta ao terminal pelo ip de cada link cadastrado
          $(".btn-type-link").click(function(){
              var ip = $(this).attr('data-ip-mon');
              var lg = $(':checkbox').is(':checked') ? 'd' : 'a';
              $("#terminal").attr('src', mountUrlTerminal(ip, lg))
          });

          //Evento que atualiza os status dos links dos estabelecimentos
          $("#refresh-links").click(function(){

            $(".refresh").html("<i class='fa fa-refresh fa-spin'></i>");

            $("#tblLinks > tbody > tr").each(function(i, v){
                checkStatusLink(v.getAttribute('data-id-link'));
            });
          });

          $('.table-data2').DataTable({
                    language: {
                      "url": '{!! url('js/traducao-table-pt-br.json') !!}'
                    },
                    autoWidth: false,
                    processing: true,
                    serverSide: true,
                    ajax: "{!! url('table-estabilishment-called') !!}?id="+$("#idEstabilishment").val(),
                    columns: [
                        {data: 'caller_number', name: 'called.caller_number'},
                        {data: 'type_link', name: 'links.type_link'},
                        {data: 'next_action', render: function(data, type, row, meta){

                            var status = '';
                            switch(data){
                                case '1':
                                    status = 'Fechado';
                                break;
                                case '2':
                                    status = 'Abertura Operadora';
                                break;
                                case '3':
                                    status = 'OTRS (Técnico)';
                                break;
                                case '4':
                                    status = 'SEMEP (Infra)';
                                break;
                                case '5':
                                    status = 'Falta de energia';
                                break;
                                case '8':
                                    status = 'Inadiplência';
                                break;
                            }

                            return status;
                        }},
                        {data: 'status', render: function(data, type, row, meta){
                            var status = '';

                            if(data == 1){
                                status = "Fechado";
                            }else if(data == 7){
                                status = "Cancelado";
                            }else{
                                status = "Aberto";
                            }

                            return status;
                        }},
                        {data: 'name', name: 'users.name'},
                        {data: 'id', render: function (data, type, row, meta) {
                            return `
                                <div class="table-data-feature">
                                    <a class="item" href="{!! url('called') !!}/${data}/edit" data-toggle="tooltip" data-placement="top" title="Visualizar">
                                        <i class="zmdi zmdi-search"></i>
                                    </a>
                                </div>
                            `;
                            }
                        }

                    ]
            });

            //Evento que informa feriado local
            $("#btn-holiday").click(function(){
                $.confirm({
                    title: 'Aviso | Sisnoc',
                    content: 'Ao informar feriado para esse estabelecimento, não será possível abrir novos chamados no dia atual. Confirma?',
                    type: 'red',
                    buttons: {
                        SIM: function () {

                          var idEstabilishment = $("#idEstabilishment").val();

                          $.post('{{url('holyday')}}/'+idEstabilishment, function(rs){
                              $.alert({
                                  title: 'Aviso | Sisnoc',
                                  type: (rs.result == true ? 'blue' : 'red'),
                                  content: rs.message
                              });

                              window.location.reload();
                          });

                        },
                        Não: function () {

                        },
                    }
                });
            })

          function mountUrlTerminal(ip, lg){
            return `http://{{ env('TERMINAL_WEB_IP') }}:8000/terminal?ip=${ip}&lg=${lg}`;
          }

          function checkStatusLink(idLink){

            $.get('{{url('ping-test')}}', {idLink : idLink}, function(r){

                console.log(r.testResults);

                if(r.testResults.retorno == true){
                    $("#" + r.link.id).html("<span class='badge badge-success'>Online</span>");
                }else if(r.testResults.msg){
                    $("#" + r.link.id).html("<span class='badge badge-warning'>Perda de Pacotes</span>");
                }else{
                    $("#" + r.link.id).html("<span class='badge badge-danger'>Offline</span>");
                }
            });
          }
      </script>
@endsection
