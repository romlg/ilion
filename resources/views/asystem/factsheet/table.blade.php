@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                {{--
                <nav class="navbar navbar-toggleable-md navbar-light bg-faded">
                    <a class="btn btn-primary" href="{{ route('material.create') }}">Добавить</a>
                </nav>
                --}}

                <div class="card">
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <th>#</th>
                                <th>Название</th>
                                <th>Количество по СА</th>
                                <th>Еденицы</th>
                                <th>Доставленно</th>
                                <th>Выработано</th>
                            </thead>
                            <tbody>

                            @foreach($data as $item)
                            <tr>
                                <td>{{ $item['material_id'] }}</td>
                                <td>{{ $item['title'] }}</td>
                                <td>{{ $item['count'] }}</td>
                                <td>{{ $item['units'] }}</td>
                                <td>{{ $item['delivered'] }}</td>
                                <td>{{ $item['processed'] }}</td>
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