@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header">
                    <h4>{{ $data['name'] }}</h4>
                </div>
                <div class="card-body">
                    @if(session()->has('message'))
                    <div class="container">
                        <div class="alert alert-success">
                            <h5>Success!</h5>
                            {{ session()->get('message') }}
                        </div>
                    </div>
                    @endif
                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <form method="POST" action="{{ route('storage.update', $id) }}">
                        @csrf
                        {{ method_field('PATCH') }}

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Season</label>
                                    <number-range :default="{{ $data['season'] }}" name="season"></number-range>
                                </div>
                                <div class="col-md-6">
                                    <label>Episode</label>
                                    <number-range :default="{{ $data['episode'] }}" name="episode"></number-range>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection