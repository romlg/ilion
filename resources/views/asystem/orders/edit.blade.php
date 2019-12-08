@extends('layouts.app')

@section('content')
@php
    /**
    @var object $order
    @var object $orderMaterials
    @var object $materials
    */
@endphp
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">

                <div class="card-body">
                    @if($order->order_id)
                    <form method="POST" action="{{ route('aorder.update', $order->order_id) }}">
                        @method('PATCH')
                    @else
                    <form method="POST" action="{{ route('aorder.store') }}">
                        <input type="hidden" value="{{ $object->object_id }}" name="object_id" />
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
                            @if($order->order_id)
                            <label for="title">Заявка от <b>{{ date("d-m-Y", strtotime($order->created_at)) }}</b></label>
                            <p>Объект {{ $order->title }}</p>
                            <p>{{ $order->post.' '.$order->last_name.' '.$order->first_name.' '.$order->middle_name.' ('.$order->phone.')' }}</p>
                            @else
                            <label for="title">Заявка от <b>@php echo date("d-m-Y") @endphp</b></label>
                            @endif
                        </div>

                        @if($order->order_id)
                        <div class="form-group row">
                            <label class="my-1 mr-2" for="selectPref">Статус</label>
                            <select name="status" class="custom-select col-md-3 mr-sm-2" id="selectPref">
                                @foreach($filterStatus As $key => $val)
                                    <option value="{{ $key }}" @if($order->status==$key) selected @endif>{{ $val }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div class="form-group">
                            <label for="material">Материалы</label>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col col-md-5">
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
                                <div class="col col-md-1">
                                    Удалить
                                </div>
                            </div>
                        </div>

                        @if($order->order_id)
                        @foreach($orderMaterials As $oneMaterial)
                        <div class="form-group">
                            <div class="row">
                                <div class="col col-md-5">
                                    <select name="material[]" class="form-control">
                                        @foreach($materials As $material)
                                            <option value="{{ $material->material_id }}" data-unit="({{ $material->units }})" data-count="{{ $material->count }}" data-cnt="{{ $material->cnt }}" @if($material->material_id == $oneMaterial->material_id) selected @endif>{{ $material->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col col-md-2">
                                    <input type="text" class="form-control mat-count" name="count[]" value="{{ $oneMaterial->count }}" data-placeholder="Количество" placeholder="Количество">
                                </div>
                                <div class="col col-md-2">
                                    <input type="text" class="form-control mat-sacount" value="" disabled="disabled">
                                </div>
                                <div class="col col-md-2">
                                    <input type="text" class="form-control mat-totalcount" value="" disabled="disabled">
                                </div>
                                <div class="col col-md-1 alert alert-danger" style="padding: 0 15px;">
                                    <button type="button" class="close" style="float: none;padding: inherit;line-height: inherit;" aria-label="Удалить">
                                        <span>x</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endif

                        <div class="form-group @if($order->order_id) d-none @endif" id="materials" >
                            <div class="row">
                                <div class="col">
                                    <select name="material[]" class="form-control">
                                        @foreach($materials As $material)
                                        <option value="{{ $material->material_id }}" data-unit="({{ $material->units }})" data-count="{{ $material->count }}" data-cnt="{{ $material->cnt }}" @if($material->material_id == $oneMaterial->material_id) selected @endif>{{ $material->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col col-md-2">
                                    <input type="text" class="form-control mat-count" name="count[]" value="{{ $oneMaterial->count }}" data-placeholder="Количество" placeholder="Количество">
                                </div>
                                <div class="col col-md-2">
                                    <input type="text" class="form-control mat-sacount" value="" disabled="disabled">
                                </div>
                                <div class="col col-md-2">
                                    <input type="text" class="form-control mat-totalcount" value="" disabled="disabled">
                                </div>
                                <div class="col col-md-1 alert alert-danger" style="padding: 0 15px;">
                                    <button type="button" class="close" style="float: none;padding: inherit;line-height: inherit;" aria-label="Удалить">
                                        <span>x</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div id="new_element"></div>

                        <div class="form-group">
                            <button type="button" class="btn btn-primary" onclick="addElement();">Добавить материал</button>
                        </div>

                        <div class="form-group">
                            <label for="text">Замечания к заявке</label>
                            <textarea id="text" name="notes" class="form-control">{{  $order->notes }}</textarea>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">Сохранить</button>

                                <a class="btn btn-primary" href="{{ route('aorder.index') }}">Закрыть</a>

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

        $('.close').on('click', function () {
            $(this).parent().parent().remove();
        });
    }

    function addElement() {
        $('.material-select').selectpicker('destroy');
        $("#materials").clone().find("input:text").val("").end().find(".row").appendTo("#new_element");
        initSelect();
    }

    $(function() {
        initSelect();
    });
</script>


@endsection
