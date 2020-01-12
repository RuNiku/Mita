@extends('layouts.app')

@section('navbar-right')
@auth
@if (!Route::has('storage/create'))
<li class="nav-item">
    <a class="nav-link" href="/storage/create"><i class="fas fa-plus-square"></i> Add</a>
</li>
@endif
@endauth
@endsection

@section('content')
@if(session()->has('message'))
<div class="container">
    <div class="alert alert-success">
        <h5>Success!</h5>
        {{ session()->get('message') }}
    </div>
</div>
@endif

<episode-view-component url="{{ url()->full() }}" search-query="hallo"></episode-view-component>
@endsection