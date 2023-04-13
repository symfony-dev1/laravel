@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="ml-2">{{ __('Categories') }}</h1>
                <div class="back-btn bredcrumb">
                    <a href="{{route('products.index')}}">Products</a><i class="fas fa-chevron-right"></i><a href="#">Categories</a>
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
                @endif
                @if (\Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {!! \Session::get('error') !!}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
                <div class="col-md-12">

                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <p class="add-edit"> {{ __('Create Category') }} </p>
                                </div>
                                <form method="POST" action="{{ route('categories.store') }}" id="categories" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" id="edit_id" name="edit_id" value="">
                                    <div class="card-body">
                                        <div class="form-group col-md-12">
                                            <label for="title" class="col-form-label text-md-right">
                                                {{ __('Title') }}<span class="mandatory_fields">*</span>
                                            </label>
                                            <div class="">
                                                <input id="title" type="text" class="form-control" name="category_title" autofocus value="{{ old('title') }}" placeholder="Enter title">
                                                @if ($errors->has('category_title'))
                                                <span class="text-danger">{{ $errors->first('category_title') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="title" class="col-form-label text-md-right">
                                                {{ __('Slug') }}
                                            </label>
                                            <div class="">
                                                <input id="slug" type="text" class="form-control" name="slug" autofocus value="{{ old('slug') }}" placeholder="Enter slug">
                                                @if ($errors->has('slug'))
                                                <span class="text-danger">{{ $errors->first('slug') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="title" class="col-form-label text-md-right">
                                                {{ __('Parent Category') }}
                                            </label>
                                            <div class="">
                                                <select name="parent_id" id="parent_id" class="form-control">
                                                    <div class="">
                                                        <option value="">None</option>
                                                    </div>
                                                    @foreach ($categories as $category)
                                                    @php $sp = ''; @endphp
                                                    <div class="">
                                                        <option value="{{ $category->id }}">{{ $category->title }}</option>
                                                    </div>
                                                    @if( $category->childs->count() > 0)
                                                    @include('products.categorychild',['categories' => $category->childs, 'sp' => $sp, 'type' => 'create'])
                                                    @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="body" class="col-form-label text-md-right">
                                                {{ __('Description') }}
                                            </label>
                                            <div class="">
                                                <textarea id="description" class="form-control tinymce-editor" name="description"> {{ old('description') }}</textarea>
                                                <span id="ck_description" style="color: red"></span>
                                                @if ($errors->has('description'))
                                                <span class="text-danger">{{ $errors->first('description') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="body" class="col-form-label text-md-right">
                                                {{ __('Image') }}
                                            </label>
                                            <div class="">
                                                <input type="file" id="image" class="form-control" name="image">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <div class="col-md-12 text-center">
                                                <button type="submit" class="btn btn-primary" id="submitBtn"> {{ __('Save') }} </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="blog-left col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    {{ __('Categories') }}
                                    <div class="d-flex align-items-center mt-2 justify-content-between">
                                        <form class="d-flex align-items-center col-md-5" action="{{route('categories.index',)}}" method="get">
                                            <input name="search" size="40" id="search" value="{{request('search')}}" class="form-control" placeholder="Search category">
                                            <div>
                                                <button id="seachBtnAction" type="submit" class="btn btn-sm ml-2 btn-primary">Search</button>
                                            </div>
                                            <a href="{{route('categories.index')}}" class="ml-2" title="Reset Search"><i class='fa fa-refresh'></i></a>
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
                                    <table class="table tableData">
                                        <thead>
                                            <tr>
                                                <td><input type="checkbox" id="selectall" class="css-checkbox " name="selectall"></td>
                                                <td>Image</td>
                                                <td>@sortablelink("title")</td>
                                                <td>@sortablelink("slug")</td>
                                                <td>@sortablelink("description")</td>
                                                <td>@sortablelink("created_at","Created Date")</td>
                                                <td>@sortablelink("updated_at","Updated Date")</td>
                                                <td>Action</td>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($categories as $category)
                                            <tr>
                                                <td><input type="checkbox" class="checkboxall" id="{{$category->id}}"></td>
                                                <td><img src="{{url('/')}}/uploads/categories/{{$category->category_image?$category->category_image:'no_image.png'}}" weight="50" height="50"></td>
                                                <td>{{ $category->title }}</td>
                                                <td>{{ $category->slug }}</td>
                                                <td>{{ $category->description }}</td>
                                                <td>{{ getFormatDate($category->created_at) }}</td>
                                                <td>{{ getFormatDate($category->updated_at) }}</td>
                                                <td>
                                                    <a href="javascript:void(0)" class="edit-category text-primary" data-id="{{$category->id}}" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                                    <a href="javascript:void(0)" class="delete-category text-danger" data-id="{{$category->id}}" title="Delete"><i class="fa-solid fa-trash"></i></a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer clearfix">
                                    {{ $categories->links() }}
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
        $(".edit-category").click(function() {
            var category_id = $(this).data('id');
            $.ajax({
                url: "{{ url('/') }}/categories/" + category_id + "/edit",
                method: "GET",
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response) {
                    if (response) {
                        var category = response;
                        $(".add-edit").html("Edit Category");
                        $("#edit_id").val(category.id);
                        $("#title").val(category.title);
                        $("#description").val(category.description);
                        $("#slug").val(category.slug);
                        $("#parent_id").val(category.parent_id);
                        $("#parent_id option[value*='" + category_id + "']").remove();
                        // $("#parent_id option[value*='" + category_id + "']").prop('disabled', true);
                    }
                }
            });

        })

        $('body').on('click', '.delete-category', function(e) {
            e.preventDefault();
            if (confirm("Are you sure you want to delete this category?")) {
                var category_id = $(this).data('id');
                $.ajax({
                    url: "{{ url('/') }}/categories/" + category_id,
                    method: "DELETE",
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    data: {
                        'category_id': category_id,
                    },
                    success: function(response) {
                        toastr.success("Category Deleted Successfully");
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
            alert("please select category")
            return
        }
        if (action == "") {
            alert("please select action")
            return
        }
        $.ajax({
            url: "{{ route('categories.bulk_action') }}",
            method: "POST",
            data: {
                "selectedIds": selectedIds,
                "action": action,
            },
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
            },
            success: function(response) {
                toastr.success("Categories Deleted Successfully");
                $(".tableData").load(document.URL + " .tableData");
            }
        });
    });
    // bulk action end
</script>
@endsection