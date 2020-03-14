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

                        <form method="POST" action="{{ route('patternPrices.update', $item->pattern_price_id) }}">

                            @method('PATCH')
                            @csrf
                            <div class="form-group">
                                <div class="row form-group">
                                    <div class="col">
                                        <label>Название</label>
                                        <input type="text" class="form-control" name="title"
                                               value="{{ $item->title }}" placeholder="Название" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <hr>
                                <label>Наменклатура</label>
                                <div class="row form-group" id="selectNomenclatures">

                                    <div class="col-12">
                                        <select name="nomenclatures" class="form-control"
                                                id="dropdownListNomenclatures">
                                            <option value="">не выбрано</option>
                                            @foreach($nomenclatures As $nomenclature)
                                                <option value="{{ $nomenclature->n_id }}"
                                                        @if($item->nomenclatures->n_id == $nomenclature->n_id) selected @endif>
                                                    {{ $nomenclature->title }}
                                                </option>
                                            @endforeach>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <hr>
                                <label>Работы</label>
                                @foreach ($item->works as $key => $patternWork)

                                    <div class="row form-group" id="selectWorks{{ $key }}">
                                        <div class="col col-md-9">
                                            <select name="works[]" class="form-control">
                                                <option value="">не выбрано</option>
                                                @foreach($works As $work)
                                                    <option value="{{ $work->work_id }}"
                                                            @if($patternWork->work_id == $work->work_id) {{ $workId = $work->work_id }} selected @endif>
                                                        {{ $work->title }}
                                                    </option>
                                                @endforeach>
                                            </select>
                                        </div>
                                        <div class="col col-md-2">
                                            <input type="number" class="form-control" name="workCount[]"
                                                   value="{{ $item->works->where('work_id', $workId)->first()->count }}"
                                                   placeholder="Кол-во" required min="1">
                                        </div>

                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-danger" name="btnWork">X</button>
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
                                <label>Шаблон материалов</label>
                                @foreach ( $item->materials as $key => $patternAdditionalMaterial )
                                    <div class="row form-group" id="selectMaterials{{ $key }}">
                                        <div class="col col-md-11">
                                            <select name="material[]" class="form-control">
                                                @foreach($patternMaterials As $patternMaterial)
                                                    <option value="{{ $patternMaterial->pattern_material_id }}"
                                                        @if( $patternAdditionalMaterial->material_id == $patternMaterial->pattern_material_id ) selected @endif>
                                                        {{ $patternMaterial->title }}
                                                    </option>
                                                @endforeach>
                                            </select>
                                        </div>

                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-danger" name="btnMaterial">X</button>
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
                                    <a class="btn btn-primary" href="{{ route('patternPrices.index') }}">Закрыть</a>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">

        var idWork = 0;
        var idMaterial = 0;

        function addElementWork() {
            $("#selectWorks0").clone(true).removeClass('d-none').find("input:text").val("").end().each(function () {
                idWork = idWork + 1;
                this.id = 'selectWork' + idWork; // to keep it unique
            }).appendTo("#new_element_works");
            initSelect();
        }

        function addElementMaterial() {
            $("#selectMaterials0").clone(true).removeClass('d-none').find("input:text").val("").end().each(function () {
                idMaterial = idMaterial + 1;
                this.id = 'selectMaterial' + idMaterial; // to keep it unique
            }).appendTo("#new_element_material");
            initSelect();
        }

        $("button[name='btnWork']").each(function (index) {
            $(this).on("click", function () {
                if ($(this).parent().parent().attr('id') != "selectWorks0") {
                    $(this).parent().parent().remove();
                } else {
                    alert("Хотя бы один пункт должен быть добавлен");
                }
            });
        });

        $("button[name='btnMaterial']").each(function (index) {
            $(this).on("click", function () {
                if ($(this).parent().parent().attr('id') != "selectMaterials0") {
                    $(this).parent().parent().remove();
                } else {
                    alert("Хотя бы один пункт должен быть добавлен");
                }
            });
        });

        $("select[name='nomenclatures']").on('change', function () {
            var optionText = $("#dropdownListNomenclatures option:selected").text();
            $("input[name='title']").val(optionText);
        });

        $(function () {
            initSelect();
        });
    </script>

@endsection
