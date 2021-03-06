@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">

                <div class="card-body">

                    <form method="POST" action="{{ route('material.update', $item->material_id) }}">
                        @csrf
                        @method('PATCH')

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
                            <div class="row form-group">
                                <div class="col">
                                    <label>Название</label>
                                    <input type="text" class="form-control" name="title"
                                           value="{{ $item->title }}" placeholder="Название">
                                </div>

                                <div class="col">
                                    <label>Артикул</label>
                                    <input type="text" class="form-control" name="vendor_code"
                                           value="{{ $item->vendor_code }}" placeholder="Артикул">
                                </div>

                                <div class="col">
                                    <label>Единица</label>
                                    <select name="unit" class="form-control">
                                        <option value="">--не выбрано--</option>
                                        @foreach($units as $key => $value)
                                        <option value="{{$key}}" @if($key == $item->unit) selected @endif>{{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col">
                                    <label>Производитель</label>
                                    <select name="producer_id" class="form-control">
                                        <option value="">--не выбрано--</option>
                                        @foreach($producers as $producer)
                                        <option value="{{$producer->producer_id}}" @if($producer->producer_id == $item->producer_id) selected @endif>
                                            {{$producer->title}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <label>Шаблон материалов</label>
                                    <select name="pattern_material_id" class="form-control">
                                        <option value="">--не выбрано--</option>
                                        @foreach($patternMaterials as $patternMaterial)
                                            <option value="{{ $patternMaterial->pattern_material_id }}" @if($patternMaterial->pattern_material_id == $item->pattern_material_id) selected @endif>
                                                {{ $patternMaterial->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col">
                                <label>Стандартная цена</label>
                                <input type="text" class="form-control" name="sprice"
                                       value="{{ $price->sprice ?? '' }}" placeholder="стандартная цена">
                            </div>

                            <div class="col">
                                <label>Оптовая цена</label>
                                <input type="text" class="form-control" name="oprice"
                                       value="{{ $price->oprice ?? '' }}" placeholder="оптовая цена">
                            </div>

                            <div class="col">
                                <label>Наша цена</label>
                                <input type="text" class="form-control" name="price"
                                       value="{{ $price->price ?? '' }}" placeholder="наша цена">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8">
                                <button type="submit" class="btn btn-primary">Сохранить</button>
                                <a class="btn btn-primary" href="{{ route('material.index') }}">Закрыть</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
