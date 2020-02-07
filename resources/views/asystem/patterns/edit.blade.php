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

                            <form method="POST" action="{{ route('pattern.update', $item->pattern_id) }}">

                                @method('PATCH')
                                @csrf
                                <div class="form-group">
                                    <div class="row form-group">
                                        <div class="col">
                                            <label>Название</label>
                                            <input type="text" class="form-control" name="title"
                                                   value=" {{ $item->title }}" placeholder="Название">
                                        </div>
                                    </div>
                                </div>



                                <div class="form-group">
                                    <hr>
                                    <label>Наменклатура</label>
                                    <div class="row form-group">
                                        <div class="col">
                                            <select name="nomenclatures[]" class="form-control" id="selectNomenclatures">
                                                @foreach($nomenclatures As $nomenclature)
                                                    <option value="{{ $nomenclature->n_id }}"
                                                        @foreach ($item->nomenclatures as $patternNomenclature)
                                                            @if($patternNomenclature->n_id == $nomenclature->n_id) selected @endif
                                                        @endforeach>{{ $nomenclature->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <hr>
                                    <label>Работы</label>
                                    <div class="row form-group">
                                        <div class="col col-md-6">
                                            <select name="works[]" class="form-control" id="selectMaterial">
                                                @foreach($works As $work)
                                                    <option value="{{ $work->work_id }}"
                                                        @foreach ($item->works as $patternWork)
                                                            @if($patternWork->work_id == $work->work_id) {{ $workId = $work->work_id }} selected @endif
                                                        @endforeach>{{ $work->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col col-md-6">
                                            <input type="text" class="form-control" name="workCount"
                                                   value="{{ $item->works->where('work_id', $workId)->first()->count }}" placeholder="Кол-во">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <hr>
                                    <label>Доп-материалы</label>
                                    <div class="row form-group">
                                        <div class="col col-md-6l">
                                            <select name="material[]" class="form-control" id="selectMaterial">
                                                @foreach($materials As $material)
                                                    <option value="{{ $material->material_id }}" data-content="({{ $material->units }})"
                                                        @foreach ($item->materials as $patternMaterial)
                                                            @if($patternMaterial->material_id == $material->material_id) {{ $materialId = $material->material_id }} selected @endif
                                                        @endforeach>{{ $material->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col col-md-6">
                                            <input type="text" class="form-control" name="materialCount"
                                                   value="{{ $item->materials->where('material_id', $materialId)->first()->count }}" placeholder="Кол-во">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-8">
                                        <button type="submit" class="btn btn-primary">Сохранить</button>
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
