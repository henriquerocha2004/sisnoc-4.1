@extends('master.master')

@section('title')
    <title>Sisnoc | Lista de Links</title>
@endsection


@section('content')

<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
                <div class="row">
                        <div class="col-md-12">
                            <input type="hidden" id="term" value="">
                            <!-- DATA TABLE -->
                            <h3 class="title-5 m-b-35">Links Cadastrados</h3>
                            @if (session('alert'))
                                @component('compoments.message', ['type' => session('alert')['messageType']])
                                    {{session('alert')['message']}}
                                @endcomponent
                            @endif

                            <div class="table-data__tool">
                                <div class="table-data__tool-right">
                                    <a href="{{route('links.create')}}" class="au-btn au-btn-icon au-btn--green au-btn--small">
                                        <i class="zmdi zmdi-plus"></i>Cadastrar</a>
                                </div>
                            </div>
                            <div class="table-responsive table-responsive-data2">
                                <table class="table table-data2">
                                    <thead>
                                        <tr>
                                            <th>Tipo do Link</th>
                                            <th>Identificação</th>
                                            <th>Estabelecimento</th>
                                            <th>Operadora</th>
                                            <th>IP Mon.</th>
                                            <th>Ações</th>
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

        var term = localStorage.getItem("term-links");

        $(function(){
            $('.table-data2').DataTable({
                    language: {
                      "url": '{!! url('js/traducao-table-pt-br.json') !!}'
                    },
                    autoWidth: false,
                    processing: true,
                    serverSide: true,
                    ajax: "{!! url('table-links')!!}",
                    columns: [
                        {data: 'type_link', name: 'type_link'},
                        {data: 'link_identification', name: 'link_identification'},
                        {data: 'establishment_code', name: 'establishment.establishment_code'},
                        {data: 'telecommunications_company', name: 'telecommunications_company'},
                        {data: 'monitoring_ip', name: 'monitoring_ip'},
                        {data: 'id', render: function (data, type, row, meta) {
                            return `
                                <div class="table-data-feature">
                                    <a class="item" href="{!! url('links') !!}/${data}/edit" data-toggle="tooltip" data-placement="top" title="Editar">
                                        <i class="zmdi zmdi-edit"></i>
                                    </a>
                                </div>
                            `;
                            }
                        }

                    ]
            }).search((term != null ? term : '')).draw();
        });

        $(".table-responsive").on('keyup', 'input[type="search"]', function(){
            localStorage.setItem('term-links', $(this).val());
        });


    </script>
@endsection
