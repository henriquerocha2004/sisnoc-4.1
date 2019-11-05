<!DOCTYPE html>
<html lang="pt-br">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="sistema de abertura de chamados para noc">
    <meta name="author" content="Henrique Rocha">

    <!-- Title Page-->
    <title>Login | SiSNOC</title>

    <!-- Fontfaces CSS-->
    <link href="{{asset('/css/fonts.css')}}" rel="stylesheet" media="all">

    <!-- Vendors CSS-->
    <link href="{{asset('/css/vendor.css')}}" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="{{asset('/css/theme.css')}}" rel="stylesheet" media="all">


</head>

<body class="animsition">
    <div class="page-wrapper">
        <div class="page-content--bge5">
            <div class="container">
                <div class="login-wrap">
                    <div class="login-content">
                        <div class="login-logo">
                            <a href="#">
                            <img src="{{url(Storage::url('images/LogoSisnoc.png'))}}" alt="logoSisnoc" style="width: 85%">
                            </a>
                        </div>
                        <div class="login-form">
                            @if (session('alert'))
                                @component('compoments.message', ['type' => session('alert')['messageType']])
                                    {{session('alert')['message']}}
                                @endcomponent
                            @endif
                            <form action="{{route('auth')}}" method="post" autocomplete="off">
                                @csrf
                                <div class="form-group">
                                    <label>Informe o Usuário</label>
                                    <input class="au-input au-input--full" type="text" name="email" placeholder="Email">
                                </div>
                                <div class="form-group">
                                    <label>Informe a senha</label>
                                    <input class="au-input au-input--full" type="password" name="password" placeholder="Senha">
                                </div>
                                <div class="col-md-8 mt-3 mb-3">
                                    <div>
                                        <label class="switch switch-text switch-primary switch-pill">
                                            <input type="checkbox" id="ad_integration" checked="" name="ad_integration" class="switch-input">
                                            <span data-on="On" data-off="Off" class="switch-label"></span>
                                            <span class="switch-handle"></span>
                                        </label>
                                        <small>Logar no sistema pelo AD</small>
                                    </div>
                                </div>
                                <button class="au-btn au-btn--block au-btn--green m-b-20" type="submit">Entrar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Jquery JS-->
    <script src="{{asset('/js/vendors.js')}}"></script>

    <!-- Main JS-->
    <script src="{{asset('/js/main.js')}}"></script>

</body>

</html>
<!-- end document-->
