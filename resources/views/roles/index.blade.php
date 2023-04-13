@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="ml-2">{{ __('Role & Permissions') }}</h1>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                @if (\Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {!! \Session::get('success') !!}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
                @if (\Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {!! \Session::get('error') !!}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
                <form action="{{  route('roles.updateRolesAndPermits') }}" method="post">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <!-- {{ __('users') }} -->
                            <div class="d-flex justify-content-between">
                                <div>
                                    {{ __('Role & Permissions') }}
                                </div>
                                <div class="d-flex">
                                    <button id="add_permission" class="btn-sm btn float-end mr-2">Add Permission</button>
                                    <button id="add_role" class="btn-sm btn float-end mr-2">Add Role</button>
                                    <button class="btn-sm btn float-end" type="submit" name="submit">Save Changes</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @foreach ($roles as $role)
                            @if ($role->name === 'admin')
                            @continue
                            @endif
                            <div class="card">
                                <div class="card-header" style="background-color:whitesmoke; color:black">
                                    @php $alloted_role = Spatie\Permission\Models\Role::findByName($role->name); @endphp
                                    <div class="d-flex align-items-center">
                                        {{ __("Role") }} :<div class="col-3"> <input type="text" value="{{$role->name}}" data-id="{{$role->id}}" id="edit_role" class="form-control"></div>
                                        <i id="delete_role" data-id="{{ $role->id }}" title="Delete Role" class="fa fa-times text-red"></i>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="container">
                                        <div class="row mt-4">
                                            @foreach ($permissions as $permission)
                                            <div class="col-md-3">
                                                <!-- <i id="edit_permission" data-id="{{ $permission->id }}" data-permission="{{$permission->name}}" title="Edit Permission" class="fa fa-edit text-primary"></i> -->
                                                <!-- <i id="delete_permission" data-id="{{ $permission->id }}" title="Delete Permission" class="fa fa-times text-red"></i> -->
                                                <label class="p_label" for="{{ $permission->name }}_{{ $role->id }}">{{Str::title(Str::replace('_', ' ', $permission->name))}}</label>
                                                <input type="checkbox" {{ $alloted_role->hasPermissionTo($permission->name)===true ? 'checked' : '' }} value="{{ $permission->name }}" name="{{ $role->name }}[]" id="{{ $permission->name }}_{{ $role->id }}">
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </form>
            </div>
            <!-- modal add role -->
            <div class="modal modal-sm" tabindex="-1" id="add_role_modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Role</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{route('roles.store')}}" method="post">
                            @csrf
                            <div class="modal-body">
                                <input type="text" name="role" id="role_name" class="form-control" placeholder="Enter role">
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- modal add/edit permission -->
            <div class="modal  modal-sm" tabindex="-1" id="permission_modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add/Edit Permission</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="form_permission_modal" action="" method="">
                            @csrf
                            <div class="modal-body">
                                <input type="text" name="permission" id="permission_name" class="form-control" placeholder="Enter permission">
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        $("#edit_id").val("");
        $(".edit-user").click(function() {
            var user_id = $(this).data('id');
            $.ajax({
                url: "{{ url('/') }}/users/" + user_id + "/edit",
                method: "GET",
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response) {
                    if (response) {
                        var user = response;
                        $(".add-edit").html("Edit user");
                        $("#edit_id").val(user.id);
                        $("#title").val(user.name);
                        $("#description").val(user.description);
                        $("#slug").val(user.slug);
                    }
                }
            });

        })

        $('body').on('click', '.delete-user', function(e) {
            e.preventDefault();
            if (confirm("Are you sure you want to delete this user?")) {
                var user_id = $(this).data('id');
                $.ajax({
                    url: "{{ url('/') }}/users/" + user_id,
                    method: "DELETE",
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function(response) {
                        toastr.success("user Deleted Successfully");
                        $(".tableData").load(document.URL + " .tableData");
                    }
                });
            }
        });

        $('body').on('click', '#delete_role', function(e) {
            e.preventDefault();
            if (confirm("Are you sure you want to delete this role?")) {
                var role_id = $(this).data('id');
                $.ajax({
                    url: "{{ url('/') }}/roles/" + role_id,
                    method: "DELETE",
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function(response) {
                        toastr.success("Role Deleted Successfully");
                        $(".content").load(document.URL + " .content");
                    }
                });
            }
        });

        $('body').on('change', '#edit_role', function(e) {
            e.preventDefault();
            if (confirm("Are you sure you want to edit this role name?")) {
                var role_id = $(this).data('id');
                var role = $(this).val();
                $.ajax({
                    url: "{{ url('/') }}/roles/" + role_id,
                    method: "PUT",
                    data: {
                        "role": role
                    },
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function(response) {
                        toastr.success("Role Updated Successfully");
                        $(".content").load(document.URL + " .content");
                    }
                });
            }
        });
    });
    // bulk action start
    $('#bulkBtnAction').click(function() {
        var array = $('tbody input:checked');
        var action = $('#bulk_action_input').val();
        var selectedIds = [];
        $.each(array, function(idx, obj) {
            selectedIds.push($(obj).attr('id'));
        });
        console.log(selectedIds);
        if (selectedIds.length == 0) {
            alert("please select user")
            return
        }
        if (action == "") {
            alert("please select action")
            return
        }
        $.ajax({
            url: "{{ route('users.bulk_action') }}",
            method: "POST",
            data: {
                "selectedIds": selectedIds,
                "action": action,
            },
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
            },
            success: function(response) {
                toastr.success("user Deleted Successfully");
                $(".tableData").load(document.URL + " .tableData");
            }
        });
    });
    // bulk action end

    // add role
    $('body').on('click', '#add_role', function(e) {
        e.preventDefault();
        $('#add_role_modal').modal('toggle');
    });
    // add role

    // add permission
    $('body').on('click', '#add_permission', function(e) {
        e.preventDefault();
        var action = "{{route('permissions.store')}}";
        $("#form_permission_modal").attr("action", action);
        $("#form_permission_modal").attr("method", "POST");
        $('#permission_modal').modal('toggle');
    });
    // add permission
</script>

@endsection