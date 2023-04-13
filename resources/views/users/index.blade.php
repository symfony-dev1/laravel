@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="ml-2">{{ __('Users') }}</h1>
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
                <div class="card">
                    <div class="card-header">
                        <!-- {{ __('users') }} -->
                        <div class="justify-content-between">
                            <div>
                                {{ __('Users') }}
                            </div>
                            <div class="d-flex align-items-center mt-2 justify-content-between">
                                <form class="d-flex align-items-center col-md-5 px-0" action="{{route('users.index',)}}" method="get">
                                    <input value="{{request('search')}}" name="search" size="50" id="search" class="form-control" placeholder="Search user" value="{{old('search')}}">
                                    <button id="seachBtnAction" type="submit" class="btn btn-sm ml-2">Search</button>
                                    <a href="{{route('users.index')}}" class="ml-2" title="Reset Search"><i class='fa fa-refresh'></i></a>
                                </form>
                                <div class="col-md-5 d-flex">

                                    <select name="bulk_action_input" id="bulk_action_input" class="form-control ml-2 d-flex" value="{{old('bulk_action_input')}}">
                                        <option value="">Bulk Action</option>
                                        <option value="delete">Delete</option>
                                    </select>
                                    <button name="bulkBtnAction" id="bulkBtnAction" class="btn btn-sm ml-2">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table tableData tableData">
                            <thead>
                                <tr>
                                    <td><input type="checkbox" id="selectall" class="css-checkbox " name="selectall"></td>
                                    <td>@sortablelink("name")</td>
                                    <td>@sortablelink("email")</td>
                                    <td>@sortablelink("created_at","Created Date")</td>
                                    <td>@sortablelink("updated_at","Updated Date")</td>
                                    <td>Action</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)

                                <tr>
                                    <td><input type="checkbox" class="checkboxall" id="{{$user->id}}"></td>
                                    <td>{{ $user->first_name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ getFormatDate($user->created_at) }}</td>
                                    <td>{{ getFormatDate($user->updated_at) }}</td>
                                    <td>
                                        <!-- <a href="{{route('users.edit',$user->id)}}" class="edit-user text-primary" data-id="{{$user->id}}" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a> -->
                                        @if($user->hasRole('admin'))
                                        @else
                                        <a href="javascript:void(0)" class="delete-user text-danger" data-id="{{$user->id}}" title="Delete"><i class="fa-solid fa-trash"></i></a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer clearfix">
                    <div class="d-flex justify-content-end">

                        {{ $users->links() }}
                    </div>                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
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
</script>

@endsection