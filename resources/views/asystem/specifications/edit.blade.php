@extends('layouts.app')

@section('content')
@php
    /** @var \App\Models\Objct $item  */
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

                    <form method="POST" action="{{ route('specification.update', $item->spec_id) }}">

                        @method('PATCH')
                        @csrf
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
                            <div class="col">
                                <label>Объекты</label>
                                <select name="object_id" class="form-control">
                                    <option value="" >Не выбран</option>
                                    @foreach($objects as $object)
                                        <option value="{{ $object->object_id }}" @if($object->object_id == $item->object_id) selected @endif>
                                            {{ $object->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8">
                                <button type="submit" class="btn btn-primary">Сохранить</button>
                                <a class="btn btn-primary" href="{{ route('specification.index') }}">Закрыть</a>

                            </div>
                        </div>
                    </form>


                </div>
            </div>
        </div>
    </div>
</div>

@endsection
