@extends('layouts.app')

@section('title', 'Работы')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

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

                <div class="form-group">
                    <a class="btn btn-primary" href="{{ route('work.create') }}">Добавить</a>
                    <a class="btn btn-primary" href="{{ route('work.upload') }}">Загрузить</a>
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
                                    <td>{{ $item->work_id }}</td>
                                    <td><a href="{{ route('work.edit', $item->work_id) }}">{{ $item->title }}</a></td>
                                    <td>{{ $item->group['title'] }}</td>
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
