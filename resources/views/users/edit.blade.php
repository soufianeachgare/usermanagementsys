@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f2937"><path d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z"/></svg> User</h2>
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

<form method="POST" action="{{ route('users.update', $user->id) }}">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="flex flex-col mb-3">
                <strong>Name:</strong>
                <input type="text" name="name" placeholder="Name" class="p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:border-blue-300" value="{{ $user->name }}">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="flex flex-col mb-3">
                <strong>Email:</strong>
                <input type="email" name="email" placeholder="Email" class="p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:border-blue-300" value="{{ $user->email }}">
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
                        <option value="{{ $value }}" {{ isset($userRole[$value]) ? 'selected' : ''}}>
                            {{ $label }}
                        </option>
                     @endforeach
                </select>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <x-primary-button>Update User</x-primary-button>
        </div>
    </div>
</form>


@endsection
