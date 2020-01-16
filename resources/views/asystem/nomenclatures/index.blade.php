@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="form-group">
                    <a class="btn btn-primary" href="{{ route('nomenclature.create') }}">Добавить</a>
                    <a class="btn btn-primary" href="{{ route('nomenclature.upload') }}">Загрузить</a>
                </div>

                <div class="card">
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                            <th>#</th>
                            <th>Название</th>
                            <th>Группа</th>
                            <th>Статус</th>
                            </thead>
                            <tbody>
                            @foreach($paginator as $item)
                                <tr>
                                    <td>{{ $item->n_id }}</td>
                                    <td><a href="{{ route('nomenclature.edit', $item->n_id) }}">{{ $item->title }}</a></td>
                                    <td>{{ $item->group_id }}</td>
                                    <td>@if($item->is_active) Активирована @else Деактивирована @endif</td>
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
