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
                                <div class="row form-group">
                                    <div class="col">
                                        <label>Материал</label>
                                        <input type="text" class="form-control" name="materials" value="{{ $fact->title }}"
                                               placeholder="Имя" disabled>
                                    </div>
                                    <div class="col">
                                        <label>Заметки</label>
                                        <input type="text" class="form-control" name="notes" value="{{ $fact->notes }}"
                                               placeholder="Заметки">
                                    </div>
                                    <div class="col">
                                        <label>Количество</label>
                                        <input type="number" class="form-control" name="count" value="{{ $fact->count }}"
                                               placeholder="Количество" required>
                                        <input type="hidden" name="fact_item_id" value="{{ $fact->id }}">
                                    </div>
                                    <div class="col">
                                        <label>Статус</label>
                                        <select name="status" class="form-control">
                                            <option value="0" @if ($fact->status == 0) selected @endif>Новый</option>
                                            <option value="1" @if ($fact->status == 1) selected @endif>Принятый</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row mb-0">
                                    <div class="col-md-8">
                                        <button type="submit" class="btn btn-primary"  >Сохранить</button>
                                        <a class="btn btn-primary" href="{{ route('fact.index') }}">Закрыть</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @endif
                    @if($fact->status===1)

                            <div class="form-group">
                                <div class="row form-group">
                                    <div class="col">
                                        <label>Материал</label>
                                        <p>{{ $fact->title }}</p>
                                    </div>
                                    <div class="col">
                                        <label>Заметки</label>
                                        <p>{{ $fact->notes }}</p>
                                    </div>
                                    <div class="col">
                                        <label>Количество</label>
                                        <p>{{ $fact->count }}</p>
                                    </div>
                                    <div class="col">
                                        <label>Статус</label>
                                        <p>Принятый</p>
                                    </div>
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
