@extends('layouts.app')

@section('content')
@php
    /** @var \App\Models\Contract $item  */
@endphp
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">

                <div class="card-body">
                    @if($item->exists)
                    <form method="POST" action="{{ route('contract.update', $item->contract_id) }}">
                        @method('PATCH')
                    @else
                    <form method="POST" action="{{ route('contract.store') }}">
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
                            <label for="title">Название</label>

                            <input id="title" type="text"
                                   class="form-control @error('title') is-invalid @enderror"
                                   name="title"
                                   value="{{ $item->title }}"
                                   required >

                            @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="date">Дата договора</label>

                            <div class="input-group date">
                                <input id="date" type="text" class="form-control @error('contract_date') is-invalid @enderror" name="contract_date" value="{{ $item->contract_date?date('m-d-Y', $item->contract_date):'' }}" required><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                            </div>

                            @error('date')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="text">Текст</label>

                            <textarea id="text" name="text" class="form-control summernote">{{ old('text', $item->text) }}</textarea>

                            @error('text')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_signed" id="is_signed" {{ $item->is_signed ? 'checked' : '' }}>

                                <label class="form-check-label" for="is_signed">Подписан</label>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">Сохранить</button>

                                <a class="btn btn-primary" href="{{ route('contract.index') }}">Закрыть</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
