@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-body">
                        @if($errors->any())
                            <div class="row justify-content-center">
                                <div class="col-md-12">
                                    <div class="alert alert-danger" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">x</span>
                                        </button>
                                        {{ $errors->first() }}
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="row justify-content-center">
                                <div class="col-md-12">
                                    <div class="alert alert-success" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">x</span>
                                        </button>
                                        {{ session()->get('success') }}
                                    </div>
                                </div>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('users.store') }}">
                            @csrf
                            <div class="form-group">
                                <div class="row form-group">
                                    <div class="col">
                                        <label>Имя</label>
                                        <input type="text" class="form-control" name="name"
                                               value="{{ old('name') }}" placeholder="Имя">
                                    </div>
                                    <div class="col">
                                        <label>Отчество</label>
                                        <input type="text" class="form-control" name="middle_name"
                                               value="{{ old('middle_name') }}" placeholder="Отчество">
                                    </div>
                                    <div class="col">
                                        <label>Фамилия</label>
                                        <input type="text" class="form-control" name="last_name"
                                               value="{{ old('last_name') }}" placeholder="Фамилия">
                                    </div>
                                    <div class="col">
                                        <label>Email</label>
                                        <input type="email" class="form-control" name="email"
                                               value="{{ old('email') }}" placeholder="Email">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col">
                                        <label>Привилегия</label>
                                        <select name="role" class="form-control">
                                            <option value="0" selected>Пользователь</option>
                                            <option value="1">Админ</option>

                                        </select>
                                    </div>
                                    <div class="col">
                                        <label>Статус</label>
                                        <select name="active" class="form-control">
                                            <option value="1" selected >Активированный</option>
                                            <option value="0" >Деактивированный</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col">
                                        <label>Номер телефона</label>
                                        <input type="phone" class="form-control" name="phone" value="{{ old('phone') }}"
                                               placeholder="Номер телефона">
                                    </div>
                                    <div class="col">
                                        <label>Дата рождения</label>
                                        <input type="text" class="form-control" name="bod" value="{{ old('bod') }}"
                                               placeholder="Дата рождения">
                                    </div>
                                    <div class="col">
                                        <label>Должность</label>
                                        <input type="text" class="form-control" name="post" value="{{ old('post') }}"
                                               placeholder="Должность">
                                    </div>
                                    <div class="col">
                                        <label>Объект</label>
                                        <select name="objects" class="form-control">
                                            <option value="" >Не выбран</option>
                                            @foreach($objects as $object)
                                                <option value="{{$object->object_id}}" >
                                                    {{$object->title}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col">
                                        <label>Пароль</label>
                                        <input type="password" class="form-control" name="password" value="">
                                    </div>
                                    <div class="col">
                                        <label>Повторить пароль</label>
                                        <input type="password" class="form-control" name="password_check" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-8">
                                    <button type="submit" class="btn btn-primary">Создать</button>

                                    <a class="btn btn-primary" href="{{ route('users.index') }}">Закрыть</a>

                                </div>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
