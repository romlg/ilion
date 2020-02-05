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

                        <form method="POST" action="{{ route('work.store') }}">

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

                            <div class="row form-group">
                                <div class="col">
                                    <label>Единица</label>
                                    <input type="text" class="form-control" name="units"
                                           value="{{ old('units') }}" placeholder="Единица">
                                </div>
                                <div class="col">
                                    <label>Время</label>
                                    <input type="text" class="form-control" name="wtime"
                                           value="{{ old('wtime') }}" placeholder="Время">
                                </div>
                                <div class="col">
                                    <label>Цена</label>
                                    <input type="text" class="form-control" name="wprice"
                                           value="{{ old('wprice') }}" placeholder="Цена">
                                </div>
                            </div>


                            <div class="row form-group">
                                <div class="col">
                                    <label>Группы</label>
                                    <select name="group_id" class="form-control">
                                        <option value="" >Не выбран</option>
                                        @foreach($groups as $group)
                                            <option value="{{ $group->group_id }}" >
                                                {{ $group->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <label>Статус</label>
                                    <select name="is_active" class="form-control">
                                        <option value="1" selected >Активированный</option>
                                        <option value="0" >Деактивированный</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8">
                                    <button type="submit" class="btn btn-primary">Создать</button>

                                    <a class="btn btn-primary" href="{{ route('work.index') }}">Закрыть</a>

                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
