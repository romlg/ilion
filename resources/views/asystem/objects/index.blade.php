@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="form-group">
                    <a class="btn btn-primary" href="{{ route('object.create') }}">Добавить</a>
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
                                <td>{{ $item->object_id }}</td>
                                <td><a href="{{ route('object.edit', $item->object_id) }}">{{ $item->title }}</a></td>
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
