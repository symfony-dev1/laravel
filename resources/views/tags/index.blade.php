@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="ml-2">{{ __('Tags') }}</h1>
                <div class="back-btn bredcrumb">
                    <a href="{{route('products.index')}}">Products</a><i class="fas fa-chevron-right"></i><a href="#">Tags</a>
                </div>
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
                Session::forget('success')
                @endif
                @if (\Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {!! \Session::get('error') !!}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                Session::forget('error')
                @endif
                <div class="col-md-12">

                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <p class="add-edit"> {{ __('Create tag') }} </p>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="{{ route('tags.store') }}" id="tags" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" id="edit_id" name="edit_id" value="">
                                        <div class="form-group col-md-12">
                                            <label for="title" class="col-form-label text-md-right">
                                                {{ __('Title') }}<span class="mandatory_fields">*</span>
                                            </label>
                                            <div class="">
                                                <input id="title" type="text" class="form-control" name="tag_title" autofocus value="{{ old('title') }}" placeholder="Enter title">
                                                @if ($errors->has('tag_title'))
                                                <span class="text-danger">{{ $errors->first('tag_title') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="title" class="col-form-label text-md-right">
                                                {{ __('Slug') }}
                                            </label>
                                            <div class="">
                                                <input id="slug" type="text" class="form-control" name="slug" autofocus value="@if( isset( $tag ) ){{ $tag->slug }}@else{{ old('slug') }}@endif" placeholder="Enter slug">
                                                @if ($errors->has('slug'))
                                                <span class="text-danger">{{ $errors->first('slug') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="body" class="col-form-label text-md-right">
                                                {{ __('Description') }}
                                            </label>
                                            <div class="">
                                                <textarea id="description" class="form-control tinymce-editor" name="description"> {{ old('description')}} </textarea>
                                                <span id="ck_description" style="color: red"></span>
                                                @if ($errors->has('description'))
                                                <span class="text-danger">{{ $errors->first('description') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <div class="col-md-12 text-center">
                                                <button type="submit" class="btn btn-primary mx-1" id="submitBtn"> {{ __('Save') }} </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="blog-left col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    {{ __('Tags') }}
                                    <div class="d-flex align-items-center mt-2 justify-content-between">
                                        <form class="d-flex align-items-center col-md-5" action="{{route('tags.index',)}}" method="get">
                                            <input name="search" size="40" id="search" value="{{request('search')}}" class="form-control" placeholder="Search tag">
                                            <div>
                                                <button id="seachBtnAction" type="submit" class="btn btn-sm ml-2 btn-primary">Search</button>
                                            </div>
                                            <a href="{{route('tags.index')}}" class="ml-2" title="Reset Search"><i class='fa fa-refresh'></i></a>
                                        </form>
                                        <div class="col-md-5 d-flex">
                                            <select name="bulk_action_input" id="bulk_action_input" class="form-control ml-2 d-flex">
                                                <option value="">Bulk Action</option>
                                                <option value="delete">Delete</option>
                                            </select>
                                            <button name="bulkBtnAction" id="bulkBtnAction" class="btn btn-sm ml-2">Apply</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table class="tableData table">
                                        <thead>
                                            <tr>
                                                <td><input type="checkbox" id="selectall" class="css-checkbox " name="selectall"></td>
                                                <td>@sortablelink("title")</td>
                                                <td>@sortablelink("slug")</td>
                                                <td>@sortablelink("description")</td>
                                                <td>@sortablelink("created_at","Created Date")</td>
                                                <td>@sortablelink("updated_at","Updated Date")</td>
                                                <td>Action</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($tags as $tag)
                                            <tr>
                                                <td><input type="checkbox" class="checkboxall" id="{{$tag->id}}"></td>
                                                <td>{{ $tag->title }}</td>
                                                <td>{{ $tag->slug }}</td>
                                                <td>{{ $tag->description }}</td>
                                                <td>{{ getFormatDate($tag->created_at) }}</td>
                                                <td>{{ getFormatDate($tag->updated_at) }}</td>
                                                <td>
                                                    <a href="javascript:void(0)" class="edit-tag text-primary" data-id="{{$tag->id}}" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                                    <a href="javascript:void(0)" class="delete-tag text-danger" data-id="{{$tag->id}}" title="Delete"><i class="fa-solid fa-trash"></i></a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer clearfix ">
                                    <!-- {{ $tags->withQueryString()->links() }} -->
                                    <div class="d-flex justify-content-end">
                                        {{ $tags->links() }}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
@endsection
@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $("#edit_id").val("");
        $(".edit-tag").click(function() {
            var tag_id = $(this).data('id');
            $.ajax({
                url: "{{ url('/') }}/tags/" + tag_id + "/edit",
                method: "GET",
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response) {
                    if (response) {
                        var tag = response;
                        $(".add-edit").html("Edit tag");
                        $("#edit_id").val(tag.id);
                        $("#title").val(tag.title);
                        $("#description").val(tag.description);
                        $("#slug").val(tag.slug);
                    }
                }
            });

        })

        $('body').on('click', '.delete-tag', function(e) {
            e.preventDefault();
            if (confirm("Are you sure you want to delete this tag?")) {
                var tag_id = $(this).data('id');
                $.ajax({
                    url: "{{ url('/') }}/tags/" + tag_id,
                    method: "DELETE",
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function(response) {
                        toastr.success("tag Deleted Successfully");
                        $(".tableData").load(document.URL + " .tableData");
                    }
                });
            }
        });
    }); // bulk action start
    $('#bulkBtnAction').click(function() {
        var array = $('tbody input:checked');
        var action = $('#bulk_action_input').val();
        var selectedIds = [];
        $.each(array, function(idx, obj) {
            selectedIds.push($(obj).attr('id'));
        });
        console.log(selectedIds);
        if (selectedIds.length == 0) {
            alert("please select tag")
            return
        }
        if (action == "") {
            alert("please select action")
            return
        }
        $.ajax({
            url: "{{ route('tags.bulk_action') }}",
            method: "POST",
            data: {
                "selectedIds": selectedIds,
                "action": action,
            },
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
            },
            success: function(response) {
                toastr.success("Tags Deleted Successfully");
                $(".tableData").load(document.URL + " .tableData");
            }
        });
    });
    // bulk action end
</script>
@endsection