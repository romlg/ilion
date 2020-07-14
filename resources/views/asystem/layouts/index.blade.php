@extends('layouts.app')
@section('title', 'Раскладка')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="form-group">
                    <a class="btn btn-primary" href="{{ route('layout.create') }}">Добавить</a>
                </div>

                <div class="card">
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <th>#</th>
                                <th>Название</th>
                            </thead>
                            <tbody>
                                @foreach($paginator as $item)
                                    <tr>
                                        <td>{{ $item->layout_id }}</td>
                                        <td><a href="{{ route('layout.edit', $item->layout_id) }}">{{ $item->title }}</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @include('includes.paginator')
    </div>

@endsection
