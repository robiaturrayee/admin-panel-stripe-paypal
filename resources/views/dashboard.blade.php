@extends('layouts.admin')

@section('content')

<h2>Dashboard</h2>

<div class="row">

    <div class="col-md-4">
        <div class="card p-3">
            <h5>Total Users</h5>
            <h3>{{ \App\Models\User::count() }}</h3>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3">
            <h5>Total Products</h5>
            <h3>{{ \App\Models\Product::count() }}</h3>
        </div>
    </div>

</div>

@endsection