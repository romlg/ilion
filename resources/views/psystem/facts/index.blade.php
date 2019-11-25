@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <nav class="navbar navbar-toggleable-md navbar-light bg-faded">
                    <a class="btn btn-primary" href="{{ route('fact.create') }}">Создать ЛФУ</a>
                </nav>

                <div class="card">
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <th>#</th>
                                <th>Дата ЛФУ</th>
                            </thead>
                            <tbody>
                            @if($paginator->total())
                            @foreach($paginator as $item)
                            <tr>
                                <td>{{ $item->object_id }}</td>
                                <td><a href="{{ route('fact.edit', $item->fact_id) }}">ЛФУ от {{ date("d-m-Y", strtotime($item->created_at)) }}</a></td>
                            </tr>
                            @endforeach
                            @else
                                <tr>
                                    <td colspan="3">У Вас пока ещё нет отчётов ЛФУ</td>
                                </tr>
                            @endif
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