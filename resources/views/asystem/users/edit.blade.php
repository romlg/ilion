@extends('layouts.app')

@section('content')
    @php
        /** @var \App\Models\Objct $item  */
    @endphp
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

                        <form method="POST" action="{{ route('users.update', $user->id) }}">
                            @method('PATCH')
                            @csrf
                            <div class="form-group">
                                <div class="row form-group">
                                    <div class="col">
                                        <label>Имя</label>
                                        <input type="text" class="form-control" name="name" value="{{ $user->name }}"
                                               placeholder="Имя">
                                    </div>
                                    <div class="col">
                                        <label>Отчество</label>
                                        <input type="text" class="form-control" name="middle_name"
                                               value="{{ $user->middle_name }}" placeholder="Отчество">
                                    </div>
                                    <div class="col">
                                        <label>Фамилия</label>
                                        <input type="text" class="form-control" name="last_name"
                                               value="{{ $user->last_name }}" placeholder="Фамилия">
                                    </div>
                                    <div class="col">
                                        <label>Email</label>
                                        <input type="email" class="form-control" name="email" value="{{ $user->email }}"
                                               placeholder="Email">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col">
                                        <label>Привилегия</label>
                                        <select name="role" class="form-control">
                                            <option value="1" @if ($user->admin == 1) selected @endif>админ</option>
                                            <option value="0" @if ($user->admin == 0) selected @endif>пользователь
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label>Статус</label>
                                        <select name="active" class="form-control">
                                            <option value="1" @if ($user->is_active == 1) selected @endif>
                                                Активированный
                                            </option>
                                            <option value="0" @if ($user->is_active == 0) selected @endif>
                                                Деактивированный
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col">
                                        <label>Номер телефона</label>
                                        <input type="phone" class="form-control" name="phone" value="{{ $user->phone }}"
                                               placeholder="Номер телефона">
                                    </div>
                                    <div class="col">
                                        <label>Bod</label>
                                        <input type="number" class="form-control" name="bod" value="{{ $user->bod }}"
                                               placeholder="Bod">
                                    </div>
                                    <div class="col">
                                        <label>Должность</label>
                                        <input type="text" class="form-control" name="post" value="{{ $user->post }}"
                                               placeholder="Должность">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-8">
                                    <button type="submit" class="btn btn-primary">Сохранить</button>

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
