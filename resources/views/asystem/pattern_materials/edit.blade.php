@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">

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

                        <form method="POST" action="{{ route('patternMaterials.update', $item->pattern_material_id) }}">

                            @method('PATCH')
                            @csrf
                            <div class="form-group">
                                <div class="row form-group">
                                    <div class="col">
                                        <label>Название</label>
                                        <input type="text" class="form-control" name="title"
                                               value=" {{ $item->title }}" placeholder="Название">
                                    </div>
                                    <div class="col">
                                        <label>Единица</label>
                                        <select name="unit" class="form-control">
                                            @foreach($units as $key => $value)
                                                <tr>
                                                    <option value="{{$key}}"
                                                            @if($item->unit == 1) selected @endif >{{$value}}</option>
                                                </tr>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8">
                                    <button type="submit" class="btn btn-primary">Сохранить</button>

                                    <a class="btn btn-primary" href="{{ route('patternMaterials.index') }}">Закрыть</a>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
