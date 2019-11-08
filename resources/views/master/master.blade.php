<!DOCTYPE html>
<html lang="pt-br">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="sistema de chamados para noc">
    <meta name="author" content="Henrique Rocha">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <!-- Title Page-->
    <title></title>

    <!-- Fontfaces CSS-->
    <link href="{{asset('/css/fonts.css')}}" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="{{asset('/css/vendor.css')}}" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="{{asset('/css/theme.css')}}" rel="stylesheet" media="all">

    @hasSection ('css')
         @yield('css')
    @endif

</head>

<body class="animsition">

        @include('sidebar.sidebar')
        <!-- PAGE CONTAINER-->
        <div class="page-container">
            <!-- HEADER DESKTOP-->
            <header class="header-desktop">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="header-wrap">
                            <form class="form-header" action="{{ route('search') }}" method="POST">
                                @csrf
                                <input class="au-input au-input--xl" type="text" name="term" placeholder="Informe chamado, cód do estabelecimento, ip ..." />
                                <button class="au-btn--submit" type="submit">
                                    <i class="zmdi zmdi-search"></i>
                                </button>
                            </form>
                            <div class="header-button">
                                <div class="account-wrap">
                                    <div class="account-item clearfix js-item-menu">

                                        <div class="content">
                                            <a class="js-acc-btn" href="#">{{auth()->user()->name}}</a>
                                        </div>
                                        <div class="account-dropdown js-dropdown">
                                            <div class="info clearfix">
                                                <div class="content">
                                                    <h5 class="name">
                                                        <a href="#">{{auth()->user()->name}}</a>
                                                    </h5>
                                                    <span class="email">{{auth()->user()->email}}</span>
                                                </div>
                                            </div>
                                            <div class="account-dropdown__body">
                                                <div class="account-dropdown__item">
                                                    <a href="{{ route('users.edit', auth()->user()->id) }}">
                                                        <i class="zmdi zmdi-account"></i>Dados da conta</a>
                                                </div>
                                                <div class="account-dropdown__item">
                                                    <a href="{{ route('config.index') }}">
                                                        <i class="zmdi zmdi-settings"></i>Configurações</a>
                                                </div>
                                            </div>
                                            <div class="account-dropdown__footer">
                                                <a href="{{route('logout')}}">
                                                    <i class="zmdi zmdi-power"></i>Sair</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- HEADER DESKTOP-->
            @yield('content')
            <!-- END PAGE CONTAINER-->

        </div>
    </div>

    <!-- Jquery JS-->
    <script src="{{asset('/js/vendors.js')}}"></script>


    <!-- Main JS-->
    <script src="{{asset('/js/main.js')}}"></script>

    @hasSection ('js')
        @yield('js')
    @endif
</body>

</html>
<!-- end document-->
