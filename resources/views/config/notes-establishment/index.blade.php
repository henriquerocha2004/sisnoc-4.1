@extends('master.master')

@section('content')

<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
                <div class="row">
                        <div class="col-md-12">
                            <!-- DATA TABLE -->
                            <h3 class="title-5 m-b-35">Notas de Estabelecimento</h3>
                            @if (session('alert'))
                                @component('compoments.message', ['type' => session('alert')['messageType']])
                                    {{session('alert')['message']}}
                                @endcomponent
                            @endif
                            <div class="table-responsive table-responsive-data2">
                                <table class="table table-data2">
                                    <thead>
                                        <tr>
                                            <th>Descrição</th>
                                            <th>Estab.</th>
                                            <th>Usuário</th>
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
                    ajax: "{!! url('notes-establishment-table')!!}",
                    columns: [
                        {data: 'desc', name: 'desc'},
                        {data: 'establishment_code', name: 'establishment_code'},
                        {data: 'name', name: 'name'},
                        {data: 'id', render: function (data, type, row, meta) {
                            return `
                                <div class="table-data-feature">
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
                    content: 'Tem Certeza que deseja remover essa nota?',
                    type: 'red',
                    buttons: {
                        SIM: function () {

                           $.ajax({
                               url: '{{ url('notes-establishment') }}/'+idCause,
                               type: 'DELETE',
                               success: function(rs){
                                $.alert({
                                    title: 'Aviso | Sisnoc',
                                    type: (rs.result == true ? 'blue' : 'red'),
                                    content: rs.message
                                });
                               }
                           });

                           window.location.reload();

                        },
                        Não: function () {},
                    }
                });

            });


        });
    </script>
@endsection
