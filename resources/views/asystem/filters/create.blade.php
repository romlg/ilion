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

                            <form method="POST" action="{{ route('filter.store') }}">

                                @csrf
                                <div class="form-group">
                                    <div class="row form-group">
                                        <div class="col">
                                            <label>Название</label>
                                            <input type="text" class="form-control" name="title"
                                                   value="{{ old('name') }}" placeholder="Название">
                                        </div>
                                    </div>

                                </div>

                                <div class="form-group">
                                    <hr>
                                    <label>Материалы</label>
                                    <div class="row form-group" id="selectMaterials">
                                        <div class="col col-md-9">
                                            <select name="material[]" class="form-control">
                                                @foreach($materials As $material)
                                                    <option value="{{ $material->material_id }}"
                                                            data-content="({{ $material->units }})">{{ $material->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col col-md-2">
                                            <input type="number" class="form-control" name="materialCount[]"
                                                   value="{{ old('materialCount') }}" placeholder="Кол-во" required>
                                        </div>

                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-danger" name="btnMaterial">X</button>
                                        </div>
                                    </div>
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
                                        <button type="submit" class="btn btn-primary">Создать</button>

                                        <a class="btn btn-primary" href="{{ route('filter.index') }}">Закрыть</a>

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
            $("#selectMaterials").clone(true).removeClass('d-none').find("input:text").val("").end().each(function(){
                idMaterial=idMaterial+1;
                this.id = 'selectMaterial' + idMaterial; // to keep it unique
            }).appendTo("#new_element_material");
            initSelect();
        }

        $("button[name='btnMaterial']").each(function(index) {
            $(this).on("click", function() {
                if($(this).parent().parent().attr('id') != "selectMaterials") {
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
