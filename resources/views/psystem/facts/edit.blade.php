@extends('layouts.app')

@section('content')
@php
    /**
    @var \App\Models\Order $item
    @var object $materials
    */
@endphp
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">

                <div class="card-body">
                    @if($item->exists)
                    <form method="POST" action="{{ route('fact.update', $item->fact_id) }}">
                        @method('PATCH')
                    @else
                    <form method="POST" action="{{ route('fact.store') }}">
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
                            <label for="title">ЛФУ от <b>@php echo date("d-m-Y") @endphp</b></label>
                            <br>
                            <label for="material">Материалы для учёта</label>
                        </div>

                        <div class="form-group" id="materials">
                            <div class="row">
                                <div class="col">
                                    <select name="material[]" class="form-control">
                                        @foreach($materials As $material)
                                            <option value="{{ $material->material_id }}" data-content="({{ $material->units }})">{{ $material->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col col-md-2">
                                    <input type="text" class="form-control" name="count[]" value="" data-placeholder="Количество" placeholder="Количество">
                                </div>
                            </div>
                        </div>

                        <div id="new_element"></div>

                        <div class="form-group">
                            <button type="button" class="btn btn-primary" onclick="addElement();">Добавить материал</button>
                        </div>

                        <div class="form-group">
                            <label for="text">Замечания к ЛФУ</label>
                            <textarea id="text" name="notes" class="form-control"></textarea>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">Создать</button>

                                <a class="btn btn-primary" href="{{ route('fact.index') }}">Закрыть</a>

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
        $('select').on('change', function() {
            var unit = $(this).find(':selected').attr('data-content');
            var placeholder_val = $(this).parent().parent().find('input').attr('data-placeholder');
            $(this).parent().parent().find('input').attr('placeholder', placeholder_val+' '+unit);
        });
    }

    function addElement() {
        $("#materials").clone().find("input:text").val("").end().appendTo("#new_element");
        initSelect();
    }

    $(function() {
        initSelect();
    });
</script>


@endsection
