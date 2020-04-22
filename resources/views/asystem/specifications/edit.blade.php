@extends('layouts.app')

@section('content')
@php
    /** @var \App\Models\Objct $item  */
@endphp
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

                    <form method="POST" action="{{ route('specification.update', $item->spec_id) }}">

                        @method('PATCH')
                        @csrf
                        <div class="form-group">
                            <div class="row form-group">
                                <div class="col">
                                    <label>Название</label>
                                    <input type="text" class="form-control" name="title"
                                           value="{{ $item->title }}" placeholder="Название">
                                </div>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col">
                                <label>Объекты</label>
                                <select name="object_id" class="form-control">
                                    <option value="" >-- не выбрано --</option>
                                    @foreach($objects as $object)
                                        <option value="{{ $object->object_id }}" @if($object->object_id == $item->object_id) selected @endif>
                                            {{ $object->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <hr>
                            <label>Наменклатура для добавления</label>
                                <div class="row form-group" id="selectNomenclatures0">
                                    <div class="col col-md-9">
                                        <select name="nomenclatures[]" class="form-control" id="selectNomenclatures">
                                            <option value="">-- не выбрано --</option>
                                            @foreach($nomenclatures As $nomenclature)
                                                <option value="{{ $nomenclature->n_id }}">
                                                    {{ $nomenclature->title }}
                                                </option>
                                            @endforeach>
                                        </select>
                                    </div>
                                    <div class="col col-md-2">
                                        <input type="number" class="form-control" name="nomenclaturesCount[]"
                                               value="" placeholder="Кол-во" min="1">
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-danger" name="btnWork">X</button>
                                    </div>
                                </div>

                            <div id="new_element_nomenclatures"></div>

                        @if (!$specUnits->isEmpty())
                            <table class="table table-hover">
                                <thead>
                                <th>#</th>
                                <th>Название</th>
                                <th>Количество</th>
                                <th>Версия</th>
                                </thead>
                                <tbody>
                                @foreach($specUnits as $specUnit)
                                    <tr>
                                        <td>{{ $specUnit->nomenclature['n_id'] }}</td>
                                        <td>{{ $specUnit->nomenclature['title'] }}</td>
                                        <td>
                                            <input type="hidden" class="form-control" name="nomenclaturesUpdate[]"
                                                   value="{{ $specUnit->sunit_id }}">
                                            <input type="number" class="form-control" name="nomenclaturesUpdateCount[]"
                                                   value="{{ $specUnit->count }}" placeholder="Кол-во" required min="1">
                                        </td>
                                        <td>{{ $specUnit->ver }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <label>Номенклатура не добавлена</label>
                        @endif

                            <div class="row form-group">
                                <div class="col">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary" name="save">Сохранить</button>&nbsp;&nbsp;
                                        <button type="submit" class="btn btn-primary" name="update">Обновить</button>&nbsp;&nbsp;
                                        <a class="btn btn-primary" href="{{ route('specification.index') }}">Закрыть</a>&nbsp;&nbsp;
                                        <a class="btn btn-primary" href="{{ route('specification.upload', $item->spec_id) }}">Загрузить</a>
                                        <button type="button" class="btn btn-primary"
                                                onclick="addElementNomenclature();">Добавить номенклатуру
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    var idNomenclature=0;

    function addElementNomenclature() {
        $("#selectNomenclatures0").clone(true).removeClass('d-none').find("input:text").val("").end().each(function(){
            idNomenclature=idNomenclature+1;
            this.id = 'selectNomenclatures' + idNomenclature; // to keep it unique
        }).appendTo("#new_element_nomenclatures");
        initSelect();
    }

    $("button[name='btnNomenclature']").each(function(index) {
        $(this).on("click", function() {
            if($(this).parent().parent().attr('id') != "selectNomenclatures0") {
                $(this).parent().parent().remove();
            } else {
                alert("Хотя бы один пункт должен быть добавлен");
            }

        });
    });

    $(function () {
        initSelect();
    });
</script>

@endsection
