@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-12">


                <div class="form-group">
                    <a class="btn btn-primary" href="{{ route('users.create') }}">Добавить пользователя</a>
                </div>

                <div class="card">
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                            <th>#</th>
                            <th>Имя</th>
                            <th>Email</th>
                            <th>Полномочия</th>
                            <th></th>
                            </thead>
                            <tbody>

                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->is_admin == 1 ? 'Администратор' : 'Пользователь'}}</td>
                                    <td><a href="{{ route('users.edit', $user->id) }}">Редактировать</a></td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
