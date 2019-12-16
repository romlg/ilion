@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">

                <div class="card-body">
                        <div class="form-group">
                            <label for="title">Заявка от <b>{{ date("d-m-Y", strtotime($order->created_at)) }} </b></label>
                        </div>

                        <div class="form-group">
                            <label for="material">Материалы</label>
                        </div>

                        @foreach($materials AS $material)
                        <div class="form-group" id="materials">
                            <div class="row">
                                <div class="col">
                                    <p>{{$material->title}}</p>
                                </div>
                                <div class="col col-md-2">
                                    <p>{{$material->count}} ({{$material->units}})</p>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        <div class="form-group">
                            <label for="text">Замечания к заявке</label>
                            <p>{{ $order->notes }}</p>
                        </div>

                        <div class="form-group">
                            <label for="text">Статус</label>
                            <p>
                            @php
                                echo App\Library\Utility::getOrderStatus($order->status);
                            @endphp
                            </p>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <a class="btn btn-primary" href="{{ route('order.index') }}">Закрыть</a>
                            </div>
                        </div>

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
