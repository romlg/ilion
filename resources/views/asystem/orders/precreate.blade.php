@extends('layouts.app')

@section('content')
@php
    /** @var \App\Models\Objct $objects  */

@endphp
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">

                <div class="card-body">

                    @php
                        /** @var \Illuminate\Support\ViewErrorBag $errors  */
                    @endphp
                    @if($errors->any())
                    <div class="form-group row">
                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <div class="alert alert-danger" role="alert">
                                    {{ $errors->first() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="form-group row">
                        <label class="my-1 mr-2" for="selectPref">Для создания заявки:</label>
                        <select class="custom-select col-md-3 mr-sm-2" id="selectPref" onChange="window.document.location.href=this.options[this.selectedIndex].value;">
                            <option value="">Выберите объект</option>
                            @foreach($objects As $object)
                                <option value="{{ route('aorder.create', ['object_id' => $object->object_id]) }}" >{{ $object->title }}</option>
                            @endforeach
                        </select>
                    </div>

{{--}
                    @if($order->order_id)
                    <form method="POST" action="{{ route('aorder.update', $order->order_id) }}">
                        @method('PATCH')
                    @else
                    <form method="POST" action="{{ route('aorder.store') }}">
                    @endif

                        @csrf


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

                        @if($order->order_id)
                        @foreach($orderMaterials As $oneMaterial)
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                    <select name="material[]" class="form-control">
                                        @foreach($materials As $material)
                                            <option value="{{ $material->material_id }}" data-content="({{ $material->units }})" @if($material->material_id == $oneMaterial->material_id) selected @endif>{{ $material->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col col-md-2">
                                    <input type="text" class="form-control" name="count[]" value="{{ $oneMaterial->count }}" data-placeholder="Количество" placeholder="Количество">
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

--}}
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
        $("#materials").clone().removeClass('d-none').find("input:text").val("").end().appendTo("#new_element");
        initSelect();
    }

    $(function() {
        initSelect();
    });
</script>


@endsection
