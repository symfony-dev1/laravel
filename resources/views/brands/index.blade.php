@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="ml-2">{{ __('Brands') }}</h1>
                <div class="back-btn bredcrumb">
                    <a href="{{route('products.index')}}">Products</a><i class="fas fa-chevron-right"></i><a href="#">Brands</a>
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
                                    <!-- @if (isset($brand))
                                        {{ __('Edit Brand') }}
                                        @else
                                        {{ __('Create Brand') }}
                                        @endif -->
                                    <p class="add-edit"> {{ __('Create Brand') }} </p>
                                </div>

                                <div class="card-body">
                                    <form method="POST" action="{{ route('brands.store') }}" id="brands" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" id="edit_id" name="edit_id" value="">
                                        <div class="form-group col-md-12">
                                            <label for="title" class="col-form-label text-md-right">
                                                {{ __('Title') }}<span class="mandatory_fields">*</span>
                                            </label>
                                            <div class="">
                                                <input id="title" type="text" class="form-control" name="brand_title" autofocus value="@if( isset( $brand ) ){{ $brand->title }}@else{{ old('title') }}@endif" placeholder="Enter title">
                                                @if ($errors->has('brand_title'))
                                                <span class="text-danger">{{ $errors->first('brand_title') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="slug" class="col-form-label text-md-right">
                                                {{ __('Slug') }}
                                            </label>
                                            <div class="">
                                                <input id="slug" type="text" class="form-control" name="slug" autofocus value="@if( isset( $brand ) ){{ $brand->slug }}@else{{ old('slug') }}@endif" placeholder="Enter slug">
                                                @if ($errors->has('slug'))
                                                <span class="text-danger">{{ $errors->first('slug') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="parent_brand" class="col-form-label text-md-right">
                                                {{ __('Parent Brand') }}
                                            </label>
                                            <div class="">
                                                <select name="parent_id" id="parent_id" class="form-control">
                                                    <div class="">
                                                        <option value="">None</option>
                                                    </div>
                                                    @foreach ($brands as $brand)
                                                    @php $sp = ''; @endphp
                                                    <div class="">
                                                        <option value="{{ $brand->id }}">{{ $brand->title }}</option>
                                                    </div>
                                                    @if( $brand->childs->count() > 0)
                                                    @include('products.brandchild',['brands' => $brand->childs, 'sp' => $sp, 'type' => 'create'])
                                                    @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="description" class="col-form-label text-md-right">
                                                {{ __('Description') }}
                                            </label>
                                            <div class="">
                                                <textarea id="description" class="form-control tinymce-editor" name="description"> @if( isset($brand) ) {{ $brand->description }} @else {{ old('description') }} @endif</textarea>
                                                <span id="ck_description" style="color: red"></span>
                                                @if ($errors->has('description'))
                                                <span class="text-danger">{{ $errors->first('description') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="image" class="col-form-label text-md-right">
                                                {{ __('Image') }}
                                            </label>
                                            <div class="">
                                                <input type="file" id="image" class="form-control" name="image">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <div class="col-md-12 text-center">
                                                <button type="submit" class="btn btn-primary mx-1" id="submitBtn">{{ __('Save') }}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="blog-left col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    {{ __('Brands') }}
                                    <div class="d-flex align-items-center mt-2 justify-content-between">
                                        <form class="d-flex align-items-center col-md-5" action="{{route('brands.index',)}}" method="get">
                                            <input name="search" size="40" id="search" value="{{request('search')}}" class="form-control" placeholder="Search brand">
                                            <div>
                                                <button id="seachBtnAction" type="submit" class="btn btn-sm ml-2 btn-primary">Search</button>
                                            </div>
                                            <a href="{{route('brands.index')}}" class="ml-2" title="Reset Search"><i class='fa fa-refresh'></i></a>
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
                                            @foreach($brands as $brand)
                                            <tr>
                                                <td><input type="checkbox" class="checkboxall" id="{{$brand->id}}"></td>
                                                <td><img src="{{url('/')}}/uploads/brands/{{$brand->brand_image?$brand->brand_image:'no_image.png'}}" weight="50" height="50"></td>
                                                <td>{{ $brand->title }}</td>
                                                <td>{{ $brand->slug }}</td>
                                                <td>{{ $brand->description }}</td>
                                                <td>{{ getFormatDate($brand->created_at) }}</td>
                                                <td>{{ getFormatDate($brand->updated_at) }}</td>
                                                <td>
                                                    <a href="javascript:void(0)" class="edit-brand text-primary" data-id="{{$brand->id}}" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                                    <a href="javascript:void(0)" class="delete-brand text-danger" data-id="{{$brand->id}}" title="Delete"><i class="fa-solid fa-trash"></i></a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer clearfix">
                            <div class="d-flex justify-content-end">

                                    {{ $brands->links() }}
                            </div>                                </div>
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
        $(".edit-brand").click(function() {
            var brand_id = $(this).data('id');
            $.ajax({
                url: "{{ url('/') }}/brands/" + brand_id + "/edit",
                method: "GET",
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response) {
                    if (response) {
                        var brand = response;
                        $(".add-edit").html("Edit brand");
                        $("#edit_id").val(brand.id);
                        $("#title").val(brand.title);
                        $("#description").val(brand.description);
                        $("#slug").val(brand.slug);
                        $("#parent_id").val(brand.parent_id);
                        $("#parent_id option[value*='" + brand_id + "']").remove();
                        // $("#parent_id option[value*='" + brand_id + "']").prop('disabled', true);
                    }
                }
            });

        })

        $('body').on('click', '.delete-brand', function(e) {
            e.preventDefault();
            if (confirm("Are you sure you want to delete this brand?")) {
                var brand_id = $(this).data('id');
                $.ajax({
                    url: "{{ url('/') }}/brands/" + brand_id,
                    method: "DELETE",
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    data: {
                        'brand_id': brand_id,
                    },
                    success: function(response) {
                        toastr.success("Brand Deleted Successfully");
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
            alert("please select brand")
            return
        }
        if (action == "") {
            alert("please select action")
            return
        }
        $.ajax({
            url: "{{ route('brands.bulk_action') }}",
            method: "POST",
            data: {
                "selectedIds": selectedIds,
                "action": action,
            },
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
            },
            success: function(response) {
                toastr.success("Brands Deleted Successfully");
                $(".tableData").load(document.URL + " .tableData");
            }
        });
    });
    // bulk action end
</script>
@endsection