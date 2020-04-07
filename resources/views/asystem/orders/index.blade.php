@extends('layouts.app')
@section('title', 'Заявки')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <nav class="navbar navbar-toggleable-md navbar-light bg-faded">
                    <a class="btn btn-primary" href="{{ route('aorder.step1') }}">Создать заявку</a>
                </nav>

                <div class="card">
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="my-1 mr-2" for="selectPref">Статус</label>
                            <select class="custom-select col-md-3 mr-sm-2" id="selectPref" onChange="window.document.location.href=this.options[this.selectedIndex].value;">
                            @foreach($filterStatus As $key => $val)
                                <option value="{{ route('aorder.index', ['status' => $key]) }}" @if($status==$key) selected @endif>{{ $val }}</option>
                            @endforeach
                            </select>
                        </div>

                        <div class="form-group row">
                            <table class="table table-hover">
                                <thead>
                                    <th>#</th>
                                    <th>Дата заявки</th>
                                    <th>Объект</th>
                                    <th>Статус</th>
                                </thead>
                                <tbody>
                                @if($paginator->total())
                                @foreach($paginator as $item)
                                <tr>
                                    <td>{{ $item->object_id }}</td>
                                    <td><a href="{{ route('aorder.edit', $item->order_id) }}">Заявка от {{ date("d-m-Y", strtotime($item->created_at)) }}</a></td>
                                    <td>{{ $item->title }}</td>
                                    <td>
                                        @php
                                            echo App\Library\Utility::getOrderStatus($item->status);
                                        @endphp
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                    <tr>
                                        <td colspan="4">В системе пока ещё нет заявок</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>

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
