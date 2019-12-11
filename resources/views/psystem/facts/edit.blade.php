@extends('layouts.app')

@section('content')
    @php
        /**    * @var \App\Models\Order $item    * @var object $materials    */
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

                        @if($fact->status===0)
                            <form method="POST" action="{{ route('fact.update', $fact->fact_id) }}">
                                @method('PATCH')
                                @csrf
                                <div class="form-group">
                                    <label for="title">ЛФУ от <b>@php echo date("d-m-Y") @endphp</b></label>
                                    <br>
                                    <label>Статус: {{ $status }}</label>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col col-md-8">
                                            <label>Материалы для учёта</label>
                                        </div>
                                        <div class="col">
                                            <label>Количество</label>
                                        </div>
                                    </div>
                                </div>

                                @foreach($materials As $material)
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col col-md-8">
                                                <input type="text" class="form-control" name="material[]"
                                                       value="{{ $material->title }}" readonly>
                                            </div>
                                            <div class="col">
                                                <input type="number" class="form-control" name="count[]"
                                                       value="{{ $material->count }}">

                                                <input type="hidden" class="form-control" name="material_id[]"
                                                       value="{{ $material->material_id }}">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <div class="row form-group">
                                    <div class="col">
                                        <label for="text">Замечания к ЛФУ</label>
                                        <textarea id="text" name="notes" class="form-control">{{ $fact->notes }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group row mb-0">
                                    <div class="col-md-8">
                                        <button type="submit" class="btn btn-primary">Сохранить</button>
                                        <a class="btn btn-primary" href="{{ route('fact.index') }}">Закрыть</a>
                                    </div>
                                </div>
                            </form>
                        @endif


                        @if($fact->status===1)

                            <div class="form-group">
                                <label for="title">ЛФУ от <b>@php echo date("d-m-Y") @endphp</b></label>
                                <br>
                                <label>Статус: {{ $status }}</label>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col col-md-8">
                                        <label>Материалы для учёта</label>
                                    </div>
                                    <div class="col">
                                        <label>Количество</label>
                                    </div>
                                </div>
                            </div>

                            @foreach($materials As $material)
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col col-md-8">
                                            <input type="text" class="form-control" name="material"
                                                   value="{{ $material->title }}" disabled>
                                        </div>
                                        <div class="col">
                                            <input type="number" class="form-control" name="count"
                                                   value="{{ $material->count }}" disabled>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="row form-group">
                                <div class="col">
                                    <label for="text">Замечания к ЛФУ</label>
                                    <textarea id="text" name="notes" class="form-control"
                                              readonly>{{ $fact->notes }}</textarea>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8">
                                    <a class="btn btn-primary" href="{{ route('fact.index') }}">Закрыть</a>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
