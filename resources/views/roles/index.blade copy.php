@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
@if (session('status') === 'Success')
<div class="alert alert-success fade show alert-dismissible" role="alert">
    {{'Permissions Updated.' }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif
@if (session('status') === 'Not allowed')
<div class="alert alert-danger fade show alert-dismissible" role="alert">
    {{'Permission Denied' }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif
<div class="container-fluid  px-4">
    <form action="{{  route('roles.updateRolesAndPermits') }}" method="post">
        @csrf
        <div class="container">
            <p class="text-right">
                <input class="btn btn-primary float-end" type="submit" value="Save Changes" name="submit">
            </p>
        </div>
        @foreach ($roles as $role)
        @if ($role->name === 'admin')
        @continue
        @endif
        <div class="card mt-5">
            <h5 class="card-header" style="background-color:whitesmoke; color:black">{{ Str::title($role->name) }}</h5>
            @php $alloted_role = Spatie\Permission\Models\Role::findByName($role->name); @endphp
            <div class="card-body">
                <div class="container">
                    <div class="row mt-4">
                        @foreach ($permissions as $permission)
                        <div class="col-md-3">
                            <label for="{{ $permission->name }}">{{Str::title(Str::replace('_', ' ', $permission->name))}}</label>
                            <input type="checkbox" {{ $alloted_role->hasPermissionTo($permission->name)===true ? 'checked' : '' }} value="{{ $permission->name }}" name="{{ $role->name }}[]" id="{{ $permission->name }}">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </form>
</div>
@stop