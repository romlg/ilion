@extends('layouts.app')

@section('content')

    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-12">
                <nav class="navbar navbar-toggleable-md navbar-light bg-faded">
                    <a class="btn btn-primary" href="{{ route('pattern.create') }}">Добавить</a>
                </nav>

                <div class="card">
                    <div class="card-body">
                        @if($paginator->total() > 0)
                            <table class="table table-hover">
                                <thead>
                                <th>#</th>
                                <th>Название</th>
                                </thead>
                                <tbody>
                                @foreach($paginator as $item)
                                    <tr>
                                        <td>{{ $item->pattern_id }}</td>
                                        <td>
                                            <a href="{{ route('pattern.edit', $item->pattern_id) }}">{{ $item->title }}</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                        @else
                            нет записей в базе данных
                        @endif
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
