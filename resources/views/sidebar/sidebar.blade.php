<div class="page-wrapper">
    <!-- HEADER MOBILE-->
    <header class="header-mobile d-block d-lg-none">
        <div class="header-mobile__bar">
            <div class="container-fluid">
                <div class="header-mobile-inner">
                    <a class="logo" href="index.html">
                        <img src="{{url(Storage::url('images/logoSisnoc-horizontal.png'))}}" alt="Sisnoc" />
                    </a>
                    <button class="hamburger hamburger--slider" type="button">
                        <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
        <nav class="navbar-mobile">
            <div class="container-fluid">
                <ul class="navbar-mobile__list list-unstyled">
                    <li class="has-sub">
                        <a class="js-arrow" href="#">
                            <i class="fas fa-home"></i>Home</a>
                    </li>
                    <li>
                        <a class="js-arrow" href="#">
                            <i class="fas fa-building"></i>Estabelecimentos</a>
                            <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                                <li>
                                    <a href="{{route('estabilishment.index')}}"> <i class="fas fa-search"></i> Consultar</a>
                                </li>
                                <li>
                                    <a href="{{route('estabilishment.create')}}"><i class="fas fa-building"></i> Novo estabelecimento</a>
                                </li>
                                <li>
                                    <a href="{{route('regionalManager.index')}}"><i class="fas fa-map"></i> Gerentes Regionais</a>
                                </li>
                                <li>
                                    <a href="{{route('technicalManager.index')}}"><i class="fas fa-user"></i> Responsáveis Técnicos</a>
                                </li>
                            </ul>
                    </li>
                    <li>
                        <a class="js-arrow" href="#">
                            <i class="fas fa-table"></i>Chamados</a>
                            <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                                <li>
                                    <a href="#"><i class="fas fa-search"></i> Consultar</a>
                                </li>
                                <li>
                                    <a href="#"><i class="fas fa-file-signature"></i> Abrir novo chamado</a>
                                </li>
                            </ul>
                    </li>
                    <li>
                        <a class="js-arrow" href="#">
                        <i class="far fa-check-square"></i>Links</a>
                        <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                            <li>
                                <a href="#"><i class="fas fa-search"></i> Consultar</a>
                            </li>
                            <li>
                                <a href="#"><i class="fas fa-file"></i> Cadastrar novo link</a>
                            </li>
                        </ul>

                    </li>
                    <li>
                        <a href="#">
                            <i class="fas fa-cogs"></i>Configurações</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- END HEADER MOBILE-->

    <!-- MENU SIDEBAR-->
    <aside class="menu-sidebar d-none d-lg-block">
        <div class="logo">
            <a href="#">
                <img src="{{url(Storage::url('images/logoSisnoc-horizontal.png'))}}" alt="sisnoc"/>
            </a>
        </div>
        <div class="menu-sidebar__content js-scrollbar1">
            <nav class="navbar-sidebar">
                <ul class="list-unstyled navbar__list">
                    <li class="has-sub">
                        <a class="js-arrow" href="#">
                            <i class="fas fa-home"></i>Home</a>
                    </li>
                    <li>
                        <a class="js-arrow" href="#">
                            <i class="fas fa-building"></i>Estabelecimentos</a>
                            <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                                <li>
                                    <a href="{{route('estabilishment.index')}}"> <i class="fas fa-search"></i> Consultar</a>
                                </li>
                                <li>
                                    <a href="{{route('estabilishment.create')}}"><i class="fas fa-building"></i> Cadastrar </a>
                                </li>
                                <li>
                                    <a href="{{route('regionalManager.index')}}"><i class="fas fa-map"></i> Gerentes Regionais</a>
                                </li>
                                <li>
                                    <a href="{{route('technicalManager.index')}}"><i class="fas fa-user"></i>Resp. Técnicos</a>
                                </li>
                            </ul>
                    </li>
                    <li>
                        <a class="js-arrow" href="#">
                            <i class="fas fa-table"></i>Chamados</a>
                            <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                                <li>
                                    <a href="{{route('called.index')}}"><i class="fas fa-search"></i> Consultar</a>
                                </li>
                                <li>
                                    <a href="{{route('called.create')}}"><i class="fas fa-file"></i> Abrir novo chamado</a>
                                </li>
                            </ul>
                    </li>
                    <li>
                        <a class="js-arrow" href="#">
                        <i class="fas fa-link"></i>Links</a>
                        <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                            <li>
                                <a href="{{route('links.index')}}"><i class="fas fa-search"></i> Consultar</a>
                            </li>
                            <li>
                                <a href="{{route('links.create')}}"><i class="fas fa-file"></i> Cadastrar novo link</a>
                            </li>
                        </ul>

                    </li>
                    <li>
                        <a href="#">
                            <i class="fas  fa-files-o"></i>Relatórios</a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fas fa-gears"></i>Configurações</a>
                    </li>

                </ul>
            </nav>
        </div>
    </aside>
    <!-- END MENU SIDEBAR-->
