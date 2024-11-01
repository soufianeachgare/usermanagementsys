@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Add Role</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary btn-sm mb-2" href="{{ route('roles.index') }}"><i class="fa fa-arrow-left"></i>
                    Back</a>
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

    <form method="POST" action="{{ route('roles.store') }}">
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
                    <strong>Permission:</strong>
                    <br />
                    <label><input type="checkbox" id="select-all"> Select All</label>
                    <br />
                    @foreach ($permission as $value)
                        <label><input type="checkbox" name="permission[]" value="{{ $value->id }}"
                                class="permission-checkbox">
                            {{ $value->name }}</label>
                        <br />
                    @endforeach
                </div>
            </div>
            <script>
                $(document).ready(function() {
                    // Handle click on "Select All" checkbox
                    $('#select-all').click(function() {
                        $('.permission-checkbox').prop('checked', this.checked);
                    });

                    // Handle click on individual checkboxes
                    $('.permission-checkbox').click(function() {
                        if (!this.checked) {
                            $('#select-all').prop('checked', false);
                        }
                        if ($('.permission-checkbox:checked').length === $('.permission-checkbox').length) {
                            $('#select-all').prop('checked', true);
                        }
                    });
                });
            </script>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <x-primary-button>Add Role</x-primary-button>
            </div>
        </div>
    </form>


@endsection
