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

                        <form method="POST" action="{{ route('pattern.update', $item->pattern_id) }}">

                            @method('PATCH')
                            @csrf
                            <div class="form-group">
                                <div class="row form-group">
                                    <div class="col">
                                        <label>Название</label>
                                        <input type="text" class="form-control" name="title"
                                               value=" {{ $item->title }}" placeholder="Название" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <hr>
                                <label>Наменклатура</label>
                                @foreach ($item->nomenclatures as $patternNomenclature)
                                    <div class="row form-group" id="selectNomenclatures">
                                        <div class="col">
                                            <select name="nomenclatures[]" class="form-control"
                                                    id="selectNomenclatures">
                                                @foreach($nomenclatures As $nomenclature)
                                                    <option value="{{ $nomenclature->n_id }}"
                                                            @if($patternNomenclature->n_id == $nomenclature->n_id) selected @endif>
                                                        {{ $nomenclature->title }}
                                                    </option>
                                                @endforeach>
                                            </select>
                                        </div>
                                    </div>
                                @endforeach
                                <div id="new_element_nomenclatures"></div>
                                <div class="row form-group">
                                    <div class="col">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-primary"
                                                    onclick="addElementNomenclature();">Добавить наменклатуру
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <hr>
                                <label>Работы</label>
                                @foreach ($item->works as $patternWork)
                                    <div class="row form-group" id="selectWork">
                                        <div class="col col-md-6">
                                            <select name="works[]" class="form-control">
                                                @foreach($works As $work)
                                                    <option value="{{ $work->work_id }}"
                                                            @if($patternWork->work_id == $work->work_id) {{ $workId = $work->work_id }} selected @endif>
                                                        {{ $work->title }}
                                                    </option>
                                                @endforeach>
                                            </select>
                                        </div>
                                        <div class="col col-md-6">
                                            <input type="text" class="form-control" name="workCount[]"
                                                   value="{{ $item->works->where('work_id', $workId)->first()->count }}"
                                                   placeholder="Кол-во" required>
                                        </div>
                                    </div>
                                @endforeach
                                <div id="new_element_works"></div>
                                <div class="row form-group">
                                    <div class="col">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-primary" onclick="addElementWork();">
                                                Добавить работу
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <hr>
                                <label>Доп-материалы</label>
                                @foreach ($item->materials as $patternMaterial)
                                    <div class="row form-group" id="selectMaterial">
                                        <div class="col col-md-6l">
                                            <select name="material[]" class="form-control">
                                                @foreach($materials As $material)
                                                    <option value="{{ $material->material_id }}"
                                                            data-content="({{ $material->units }})"
                                                            @if($patternMaterial->material_id == $material->material_id) {{ $materialId = $material->material_id }} selected @endif>
                                                        {{ $material->title }}
                                                    </option>
                                                @endforeach>
                                            </select>
                                        </div>
                                        <div class="col col-md-6">
                                            <input type="text" class="form-control" name="materialCount[]"
                                                   value="{{ $item->materials->where('material_id', $materialId)->first()->count }}"
                                                   placeholder="Кол-во" required>
                                        </div>
                                    </div>
                                @endforeach
                                <div id="new_element_material"></div>
                                <div class="row form-group">
                                    <div class="col">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-primary"
                                                    onclick="addElementMaterial();">Добавить материал
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>
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

    <script type="text/javascript">
        function addElementNomenclature() {
            $("#selectNomenclatures").clone().removeClass('d-none').find("input:text").val("").end().appendTo("#new_element_nomenclatures");
            initSelect();
        }

        function addElementWork() {
            $("#selectWork").clone().removeClass('d-none').find("input:text").val("").end().appendTo("#new_element_works");
            initSelect();
        }

        function addElementMaterial() {
            $("#selectMaterial").clone().removeClass('d-none').find("input:text").val("").end().appendTo("#new_element_material");
            initSelect();
        }

        $(function () {
            initSelect();
        });
    </script>


@endsection
