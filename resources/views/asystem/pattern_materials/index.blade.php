@extends('layouts.app')

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

                @if(session('error'))
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="alert alert-danger" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">x</span>
                                </button>
                                {{ session()->get('error') }}
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('patternMaterials.copy') }}" method="post">

                    @csrf

                    <div class="form-group">
                        <a class="btn btn-primary" href="{{ route('patternMaterials.create') }}">Добавить</a>
                        <input type="submit" value="Копировать" class="btn btn-primary">
                    </div>

                    <div class="card">
                        <div class="card-body">
                            @if($paginator->total() > 0)
                                <table class="table table-hover">
                                    <thead>
                                    <th>#</th>
                                    <th>Название</th>
                                    </thead>
                                    <tbody>
                                    <div class="form-group">
                                        @foreach($paginator as $item)
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                    <input type="checkbox" class="form-check-input"
                                                           value="{{ $item->pattern_material_id }}" name="pattern[]">
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="{{ route('patternMaterials.edit', $item->pattern_material_id) }}">{{ $item->title }}</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </div>
                                    </tbody>
                                </table>

                            @else
                                нет записей в базе данных
                            @endif
                        </div>
                    </div>
                </form>

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
