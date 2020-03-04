<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Ilion</title>

    <!-- Scripts -->
    <!-- include jquery -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.js"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- include summernote -->
    <link rel="stylesheet" href="{{ asset('plugin/summernote/summernote.css') }}">
    <script type="text/javascript" src="{{ asset('plugin/summernote/summernote.js') }}"></script>

    <!-- include datepicker -->
    <link rel="stylesheet" href="{{ asset('plugin/datepicker/bootstrap-datepicker.min.css') }}">
    <script type="text/javascript" src="{{ asset('plugin/datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugin/datepicker/locales/bootstrap-datepicker.ru.min.js') }}" charset="UTF-8"></script>

    <!-- include bootstrap-select -->
    <link rel="stylesheet" href="{{ asset('plugin/bootstrap-select/bootstrap-select.min.css') }}">
    <script type="text/javascript" src="{{ asset('plugin/bootstrap-select/bootstrap-select.min.js') }}"></script>

    <script type="text/javascript">
        $(function() {
            $('.summernote').summernote({
                height: 400,
            });
            $('.input-group.date').datepicker({
                format: "dd-mm-yyyy",
                language: "ru",
                multidate: false,
                autoclose: true
            });
        });
    </script>

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    Ilion
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">

                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Вход</a>
                            </li>
                        @else

{{--                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('factsheet.index') }}">Отчёт объекта</a>
                                </li>--}}

                            @if(!Auth::user()->isAdmin() && Auth::user()->isActive())
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('fact.index') }}">ЛФУ</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('order.index') }}">Заявки</a>
                                </li>
                            @endif

                            <!-- Authentication Links -->
                            @if(Auth::user()->isAdmin())
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Справочник <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('nomenclature.index') }}">Номенклатура</a>
                                    <a class="dropdown-item" href="{{ route('pattern.index') }}">Шаблоны</a>
                                    <a class="dropdown-item" href="{{ route('work.index') }}">Работа</a>
                                    <a class="dropdown-item" href="{{ route('filter.index') }}">Фильтры</a>
                                    <a class="dropdown-item" href="{{ route('object.index') }}">Объекты</a>
                                    <a class="dropdown-item" href="{{ route('material.index') }}">Материалы</a>
                                    <a class="dropdown-item" href="{{ route('group.index') }}">Группы</a>
                                    <a class="dropdown-item" href="{{ route('users.index') }}">Пользователи</a>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Документ <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('specification.index') }}">Спецификации</a>
                                    <a class="dropdown-item" href="{{ route('contract.index') }}">Документы</a>
                                    <a class="dropdown-item" href="{{ route('aorder.index') }}">Заявки</a>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Иное <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('home') }}" />
                                    Кабинет
                                    </a>
                                </div>
                            </li>
                            @endif

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('home') }}" />
                                        Кабинет
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest


                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
