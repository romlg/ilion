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

                            <form method="POST" action="{{ route('patternMaterials.update', $item->pattern_material_id) }}">

                                @method('PATCH')
                                @csrf
                                <div class="form-group">
                                    <div class="row form-group">
                                        <div class="col">
                                            <label>Название</label>
                                            <input type="text" class="form-control" name="title"
                                                   value=" {{ $item->title }}" placeholder="Название">
                                        </div>
                                        <div class="col">
                                            <label>Единица</label>
                                            <select name="unit" class="form-control">
                                                @foreach($units as $key => $value)
                                                    <tr>
                                                        <option value="{{$key}}" @if($item->unit == 1) selected @endif >{{$value}}</option>
                                                    </tr>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-8">
                                        <button type="submit" class="btn btn-primary">Сохранить</button>

                                        <a class="btn btn-primary" href="{{ route('patternMaterials.index') }}">Закрыть</a>

                                    </div>
                                </div>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript">

        var idMaterial=0;

        function addElementMaterial() {
            $("#selectMaterials0").clone(true).removeClass('d-none').find("input:text").val("").end().each(function(){
                idMaterial=idMaterial+1;
                this.id = 'selectMaterial' + idMaterial; // to keep it unique
            }).appendTo("#new_element_material");
            initSelect();
        }

        $("button[name='btnMaterial']").each(function(index) {
            $(this).on("click", function() {
                if($(this).parent().parent().attr('id') != "selectMaterials0") {
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
