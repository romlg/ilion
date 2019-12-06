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
                    @if($order->order_id)
                    <form method="POST" action="{{ route('order.update', $order->order_id) }}">
                        @method('PATCH')
                    @else
                    <form method="POST" action="{{ route('order.store') }}">
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
                            <label for="title">Заявка от <b>@php echo date("d-m-Y") @endphp</b></label>
                            @if(date("H") > 12)
                            <div class="alert alert-danger" role="alert">
                            Ваша заявка будет обработана завтра, если она срочная согласовывайте с начальником по производству
                            </div>
                            @endif
                            <br>
                            <label for="material">Материалы</label>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col col-md-6">
                                    Наименование
                                </div>
                                <div class="col col-md-2">
                                    Количество для заявки
                                </div>
                                <div class="col col-md-2">
                                    Количество по СА
                                </div>
                                <div class="col col-md-2">
                                    Уже доставленно
                                </div>
                                {{--<div class="col col-md-1">
                                    Удалить
                                </div>--}}
                            </div>
                        </div>



                        @foreach($orderMaterials As $oneMaterial)
                            <div class="form-group" id="materials">
                                <div class="row">
                                    <div class="col col-md-6">
                                        <select name="material[]" class="form-control">
                                            @foreach($materials As $material)
                                                <option value="{{ $material->material_id }}" data-content="({{ $material->units }})" @if($material->material_id == $oneMaterial->material_id) selected @endif>{{ $material->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col col-md-2">
                                        <input type="text" class="form-control" name="count[]" value="{{ $oneMaterial->count }}" data-placeholder="Количество" placeholder="Количество">
                                    </div>
                                    <div class="col col-md-2">
                                        <input type="text" class="form-control mat-sacount" value="" disabled="disabled">
                                    </div>
                                    <div class="col col-md-2">
                                        <input type="text" class="form-control mat-totalcount" value="" disabled="disabled">
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div id="new_element"></div>


                        <div id="new_element"></div>

                        <div class="form-group">
                            <button type="button" class="btn btn-primary" onclick="addElement();">Добавить материал</button>
                        </div>

                        <div class="form-group">
                            <label for="text">Замечания к заявке</label>
                            <textarea id="text" name="notes" class="form-control">{{ $order->notes }}</textarea>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">Сохранить</button>

                                <a class="btn btn-primary" href="{{ route('order.index') }}">Закрыть</a>

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
