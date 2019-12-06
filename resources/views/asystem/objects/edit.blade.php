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
                    @if($item->exists)
                    <form method="POST" action="{{ route('object.update', $item->object_id) }}">
                        @method('PATCH')
                    @else
                    <form method="POST" action="{{ route('object.store') }}">
                    @endif

                        @csrf
                        @php
                        /** @var \Illuminate\Support\ViewErrorBag $errors  */
                        @endphp
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
                            <label for="title">Название</label>

                            <input id="title" type="text"
                                   class="form-control @error('title') is-invalid @enderror"
                                   name="title"
                                   value="{{ $item->title }}"
                                   required >

                            <br>
                            <div class="col col-md-12">
                                <label>Материалы</label>
                                <table>
                                    <tr>
                                        <th>Название</th>
                                        <th>Количество</th>
                                    </tr>

                                    @foreach($materials As $material)
                                        <tr>
                                            <td>{{ $material->title }}</td>
                                            <td>{{ $material->count }} {{ $material->units }}</td>

                                        </tr>
                                    @endforeach
                                    </table>
                            </div>

                            @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div id="new_element"></div>

                        <div class="form-group" id="materials">
                            <div class="row">
                                <div class="col col-md-6">
                                    <select name="material[]" class="form-control material-select" data-live-search="true">
                                        @foreach($materialsAll As $material)
                                            <option value="{{ $material->material_id }}" data-unit="({{ $material->units }})" data-count="{{ $material->count }}">{{ $material->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col col-md-2">
                                    <input type="number" class="form-control mat-count" name="count[]" value="" data-placeholder="Количество" placeholder="Количество" required>
                                    <input type="hidden"class="form-control mat-count-unit" name="units[]" value="{{ $material->units }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8">

                                <button type="submit" class="btn btn-primary">Сохранить</button>

                                <a class="btn btn-primary" href="{{ route('object.index') }}">Закрыть</a>

                                <button type="button" class="btn btn-primary" onclick="addElement();">Добавить материал</button>

                                <a class="btn btn-primary" href="{{ route('object.upload', $item->object_id) }}">Загрузить материалы</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">

    function initSelect() {
        //$('select').on('change', function() {

        $('.material-select').selectpicker();

        $('.material-select').on('change', function() {
            var unit = $(this).find(':selected').attr('data-unit');
            var placeholder_val = $(this).parent().parent().find('input.mat-count').attr('data-placeholder');
            $(this).parent().parent().find('input.mat-count').attr('placeholder', placeholder_val+' '+unit);
            $(this).parent().parent().find('input.mat-count-unit').attr('value', unit);

            var sacount = $(this).find(':selected').attr('data-count');
            $(this).parent().parent().find('input.mat-sacount').val(sacount);
            //
            var cnt = $(this).find(':selected').attr('data-cnt');
            $(this).parent().parent().find('input.mat-totalcount').val($(this).find(':selected').attr('data-cnt'));
            //
        });
        //  $('.material-select').selectpicker();
    }

    function addElement() {
        $('.material-select').selectpicker('destroy');
        $("#materials").clone().find("input:text").val("").end().appendTo("#new_element");
        initSelect();
    }

    $(function() {
        //$('.material-select').selectpicker();
        initSelect();
    });
</script>

@endsection
