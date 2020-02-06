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

                        <form method="POST" action="{{ route('pattern.store') }}">

                            @csrf
                            <div class="form-group">
                                <div class="row form-group">
                                    <div class="col">
                                        <label>Название</label>
                                        <input type="text" class="form-control" name="title"
                                               value="{{ old('name') }}" placeholder="Название">
                                    </div>
                                </div>

                            </div>
                            <div class="form-group">
                                <div class="row form-group">
                                    <div class="col">
                                        <hr>
                                        <label>Наменклатура</label>
                                        <select name="nomenclatures[]" class="form-control" id="selectNomenclatures">
                                            @foreach($nomenclatures As $nomenclature)
                                                <option value="{{ $nomenclature->n_id }}">{{ $nomenclature->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row form-group">
                                    <div class="col">
                                        <hr>
                                        <label>Работы</label>
                                        <select name="works[]" class="form-control" id="selectMaterial">
                                            @foreach($works As $work)
                                                <option value="{{ $work->work_id }}">{{ $work->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row form-group">
                                    <div class="col">
                                        <hr>
                                        <label>Доп-материалы</label>
                                        <select name="material[]" class="form-control" id="selectMaterial">
                                            @foreach($materials As $material)
                                                <option value="{{ $material->material_id }}" data-content="({{ $material->units }})">{{ $material->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group row mb-0">
                                <div class="col-md-8">
                                    <button type="submit" class="btn btn-primary">Создать</button>
                                    <a class="btn btn-primary" href="{{ route('pattern.index') }}">Закрыть</a>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
