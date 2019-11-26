@extends('master.master')

@section('title')
    <title>Sisnoc | Lista de Chamados </title>
@endsection

@section('content')

<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
                <div class="row">
                        <div class="col-md-12">
                            <!-- DATA TABLE -->
                            <h3 class="title-5 m-b-35">Chamados Gerados</h3>
                            @if (session('alert'))
                                @component('compoments.message', ['type' => session('alert')['messageType']])
                                    {{session('alert')['message']}}
                                @endcomponent
                            @endif

                            <div class="table-data__tool">
                                <div class="table-data__tool-right">
                                    <a href="{{route('called.create')}}" class="au-btn au-btn-icon au-btn--green au-btn--small">
                                        <i class="zmdi zmdi-plus"></i>Abrir Novo Chamado</a>
                                </div>
                            </div>
                            <div class="table-responsive table-responsive-data2">
                                <table class="table table-data2">
                                    <thead>
                                        <tr>
                                            <th>Chamado</th>
                                            <th>Estabelecimento</th>
                                            <th>Link</th>
                                            <th>Ultima Ação</th>
                                            <th>Situação</th>
                                            <th>Aberto Por</th>
                                        </tr>
                                    </thead>

                                </table>
                            </div>
                            <!-- END DATA TABLE -->
                        </div>
                    </div>
        </div>
    </div>
</div>

@endsection

@section('js')
    <script src="{{asset('js/datatables.js')}}"></script>
    <script>
//tetsttststts
        var term = localStorage.getItem("term-called");

        $(function(){
            $('.table-data2').DataTable({
                    language: {
                      "url": '{!! url('js/traducao-table-pt-br.json') !!}'
                    },
                    autoWidth: false,
                    processing: true,
                    serverSide: true,
                    ajax: "{!! url('table-called')!!}",
                    columns: [
                        {data: 'caller_number', name: 'called.caller_number'},
                        {data: 'establishment_code', name: 'establishment.establishment_code'},
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
            }).search((term != null ? term : '')).draw();

            $(".table-responsive").on('keyup', 'input[type="search"]', function(){
                localStorage.setItem('term-called', $(this).val());
            });
        });
    </script>
@endsection
