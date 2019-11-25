@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <nav class="navbar navbar-toggleable-md navbar-light bg-faded">
                    <a class="btn btn-primary" href="{{ route('contract.create') }}">Добавить</a>
                </nav>

                <div class="card">
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <th>#</th>
                                <th>Название</th>
                                <th>Дата</th>
                                <th>Подписан</th>
                            </thead>
                            <tbody>
                            @foreach($paginator as $item)
                            <tr>
                                <td>{{ $item->contract_id }}</td>
                                <td><a href="{{ route('contract.edit', $item->contract_id) }}">{{ $item->title }}</a></td>
                                <td>{{ date("Y-m-d", $item->contract_date) }}</td>
                                <td>{{ $item->is_signed ? 'Да' : 'Нет' }}</td>
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