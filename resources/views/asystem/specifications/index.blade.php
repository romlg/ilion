@extends('layouts.app')
@section('title', 'Спецификация')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <nav class="navbar navbar-toggleable-md navbar-light bg-faded">
                    <a class="btn btn-primary" href="{{ route('specification.create') }}">Добавить</a>
                </nav>

                <div class="card">
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <th>#</th>
                                <th>Название</th>
                                <th>Объект</th>
                            </thead>
                            <tbody>
                                @foreach($paginator as $item)
                                    <tr>
                                        <td>{{ $item->spec_id }}</td>
                                        <td><a href="{{ route('specification.edit', $item->spec_id) }}">{{ $item->title }}</a></td>
                                        <td>{{ $item->object['title'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @if($paginator->total() > $paginator->count())
            <br>
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            {{ $paginator->links() }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

@endsection
