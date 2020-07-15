@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">

                <div class="card-body">
                    
                    @include('includes.alert.error')
                    @include('includes.alert.success')

                    <form id="layout_edit_form" method="POST" action="{{ route('layout.update', $item->layout_id) }}">
                        @method('PATCH')
                        @csrf
                        <input type="hidden" id="calculate_co" name="calculate_co" value=0 />
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
                            <table class="table table-hover" id="layout-data">
                                <thead>
                                    <tr>
                                        <th class="position-sticky bg-light" style="top:5px;">Позиция</th>
                                        <th class="position-sticky bg-light" style="top:5px;">Наименование</th>
                                        <th class="position-sticky bg-light" style="top:5px;">Количество</th>
                                        <th class="position-sticky bg-light" style="top:5px;">Цена</th>
                                        <th class="position-sticky bg-light" style="top:5px;">Сумма</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($item->layoutMaterial as $lm)
                                <tr class="single-position" 
                                    data-price="{{ $lm->getPositionPrice() }}"
                                    data-sum="{{ $lm->getPositionSum() }}"
                                    data-count="{{ $lm->count }}">
                                        <td>{{ $lm->position_id }}</td>
                                        <td>
                                            @if($lm->isPattern())
                                            <select name="material_to_pattern_material[{{$lm->id}}]" class="form-control material-select">
                                                <option value>-- не выбрано --</option>
                                                @foreach($lm->position->materials as $material)
                                                <option
                                                    value="{{ $material->material_id }}"
                                                    data-price="{{ $material->price ?? 0 }}"
                                                    @if($lm->selectedMaterial && $lm->selectedMaterial->material_id === $material->material_id) selected @endif>
                                                    {{ $material->title }} ({{ $material->price ?? 0 }})
                                                </option>
                                                @endforeach
                                            </select>
                                            @else
                                                {{ $lm->position->title ?? 'XXX' }}
                                            @endif
                                        </td>
                                        <td>{{ $lm->count }}</td>
                                        <td class="single-position-price">{{ $lm->getPositionPrice() }}</td>
                                        <td class="single-position-sum">{{ $lm->getPositionSum() }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                  <tr>
                                    <th class="position-sticky bg-light" style="bottom:5px;">
                                        <button type="submit" class="btn btn-primary form-submitter" name="save">Сохранить</button>
                                    </th>
                                    <th class="position-sticky bg-light" style="bottom:5px;">
                                        <button type="submit" class="btn btn-primary form-submitter" name="final_co">Итоговое КП</button>
                                    </th>
                                    <th colspan="2" class="text-right position-sticky bg-light" style="bottom:5px;">Итого</th>
                                    <th id="total-amount" class="position-sticky bg-light" style="bottom:5px;">0</th>
                                  </tr>
                                </tfoot>
                            </table>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#layout-data').change(function() {
        var total = $('.single-position').toArray().reduce(function(sum, el) {
            console.log('current sum', el.dataset.sum);
            return sum + parseInt(el.dataset.sum);
        }, 0);
        $('#total-amount').text(total);
    });
    
    $('.single-position').on('price-changed', function(e, price) {
        var target = e.target,
            price = parseInt(price),
            sum
        ;

        target.dataset.price = price;
        sum = target.dataset.count * price;
        target.dataset.sum = sum;

        target = $(target);
        target.find('.single-position-price').text(price);
        target.find('.single-position-sum').text(sum);
    });
   
    $('.material-select').change(function(e) {
        var select = e.target,
            option = select[select.selectedIndex],
            tr = $(select).parents('.single-position');

        if (option && option.dataset) {
            tr.trigger('price-changed', option.dataset.price || 0);
        }
    });
    
    $('.form-submitter').click(function(e) {
       e.preventDefault();
       var form = $('#layout_edit_form'),
           inputCalculateCO = form.find('#calculate_co');

       if ($(this).attr('name') === 'final_co') {
           var selects = form.find('select');
           for (var i = 0; i < selects.length; i++) {
               if (!selects[i].value) {
                   alert("Недостаточно данных для создания КП. Должны быть выбраны все соответствующие материалы");
                   return;
               }
           }
           inputCalculateCO.val(1);
       } else {
           inputCalculateCO.val(0);
       }
       form.submit();
    });
    
    $(function() {
        $('#layout-data').change();
    });

</script>

@endsection
