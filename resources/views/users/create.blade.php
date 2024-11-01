@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Add User</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary btn-sm mb-2" href="{{ route('users.index') }}"><i class="fa fa-arrow-left"></i> Back to list</a>
        </div>
    </div>
</div>

@if (count($errors) > 0)
    <div class="alert alert-danger">
      <strong>Whoops!</strong> There were some problems with your input.<br><br>
      <ul>
         @foreach ($errors->all() as $error)
           <li>{{ $error }}</li>
         @endforeach
      </ul>
    </div>
@endif

<form method="POST" action="{{ route('users.store') }}">
    @csrf
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="flex flex-col mb-3">
                <strong>Name:</strong>
                <input type="text" name="name" placeholder="Name" class="p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:border-blue-300">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="flex flex-col mb-3">
                <strong>Email:</strong>
                <input type="email" name="email" placeholder="Email" class="p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:border-blue-300">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="flex flex-col mb-3">
                <strong>Password:</strong>
                <input type="password" name="password" placeholder="Password" class="p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:border-blue-300">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="flex flex-col mb-3">
                <strong>Confirm Password:</strong>
                <input type="password" name="confirm-password" placeholder="Confirm Password" class="p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:border-blue-300">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="flex flex-col mb-3">
                <strong>Role:</strong>
                <select name="roles" class="p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:border-blue-300" multiple>
                    @foreach ($roles as $value => $label)
                        <option value="{{ $value }}">
                            {{ $label }}
                        </option>
                     @endforeach
                </select>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <x-primary-button>Add User</x-primary-button>
        </div>
    </div>
</form>


@endsection