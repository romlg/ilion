@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <nav class="navbar navbar-toggleable-md navbar-light bg-faded">
                    <a class="btn btn-primary" href="{{ route('order.create') }}">Создать заявку</a>
                </nav>

                <div class="card">
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <th>#</th>
                                <th>Дата заявки</th>
                                <th>Статус</th>
                            </thead>
                            <tbody>
                            @if($paginator->total())
                            @foreach($paginator as $item)
                            <tr>
                                <td>{{ $item->object_id }}</td>
                                <td><a href="{{ route('order.edit', $item->order_id) }}">Заявка от {{ date("d-m-Y", strtotime($item->created_at)) }}</a></td>
                                <td>
                                    @php
                                        echo App\Library\Utility::getOrderStatus($item->status);
                                    @endphp
                                </td>
                            </tr>
                            @endforeach
                            @else
                                <tr>
                                    <td colspan="3">У Вас пока ещё нет заявок</td>
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