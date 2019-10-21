@extends('master.master')

@section('content')

<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
                <div class="row">
                        <div class="col-md-12">
                            <!-- DATA TABLE -->
                            <h3 class="title-5 m-b-35">Causas de Problemas</h3>
                            @if (session('alert'))
                                @component('compoments.message', ['type' => session('alert')['messageType']])
                                    {{session('alert')['message']}}
                                @endcomponent
                            @endif

                            <div class="table-data__tool">
                                <div class="table-data__tool-right">
                                    <a href="{{route('cause-problem.create')}}" class="au-btn au-btn-icon au-btn--green au-btn--small">
                                        <i class="zmdi zmdi-plus"></i>Cadastrar</a>
                                </div>
                            </div>
                            <div class="table-responsive table-responsive-data2">
                                <table class="table table-data2">
                                    <thead>
                                        <tr>
                                            <th>Descrição</th>
                                            <th>Ação</th>
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
        $(function(){
            $('.table-data2').DataTable({
                    language: {
                      "url": '{!! url('js/traducao-table-pt-br.json') !!}'
                    },
                    autoWidth: false,
                    processing: true,
                    serverSide: true,
                    ajax: "{!! url('cause-problem-table')!!}",
                    columns: [
                        {data: 'description_cause', name: 'description_cause'},
                        {data: 'id', render: function (data, type, row, meta) {
                            return `
                                <div class="table-data-feature">
                                    <a class="item" href="{!! url('cause-problem') !!}/${data}/edit" data-toggle="tooltip" data-placement="top" title="Editar">
                                        <i class="zmdi zmdi-edit"></i>
                                    </a>
                                    <a class="item" id="btn-delete" data-id-delete="${data}" href="#" data-toggle="tooltip" data-placement="top" title="Editar">
                                        <i class="zmdi zmdi-delete"></i>
                                    </a>
                                </div>
                            `;
                            }
                        }

                    ]
            });

            $(".table-data2").on('click', '#btn-delete',function(){

                var idCause = $(this).attr('data-id-delete');

                $.confirm({
                    title: 'Aviso | Sisnoc',
                    content: 'Tem Certeza que deseja remover essa causa de problema?',
                    type: 'red',
                    buttons: {
                        SIM: function () {

                           $.ajax({
                               url: '{{ url('cause-problem') }}/'+idCause,
                               type: 'DELETE',
                               success: function(rs){
                                $.alert({
                                    title: 'Aviso | Sisnoc',
                                    type: (rs.result == true ? 'blue' : 'red'),
                                    content: rs.message
                                });
                               }
                           });

                           window.location.reload;

                        },
                        Não: function () {},
                    }
                });

            });


        });
    </script>
@endsection
