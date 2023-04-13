@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="ml-2">{{ __('Attributes') }}</h1>
                <div class="back-btn bredcrumb">
                    <a href="{{route('products.index')}}">Products</a><i class="fas fa-chevron-right"></i><a href="#">Attributes</a>
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

                    <input type="hidden" id="edit_id" name="edit_id" value="">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <p class="add-edit"> {{ __('Create Attribute') }} </p>
                                </div>
                                <form method="POST" action="{{ route('attributes.store') }}" id="attributes" enctype="multipart/form-data">
                                    @csrf
                                    <div class="card-body">
                                        <div class="form-group col-md-12">
                                            <label for="title" class="col-form-label text-md-right">
                                                {{ __('Title') }}<span class="mandatory_fields">*</span>
                                            </label>
                                            <div class="">
                                                <input id="title" type="text" class="form-control" name="attribute_title" autofocus value="{{ old('title') }}" placeholder="Enter title">
                                                @if ($errors->has('attribute_title'))
                                                <span class="text-danger">{{ $errors->first('attribute_title') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="title" class="col-form-label text-md-right">
                                                {{ __('Slug') }}
                                            </label>
                                            <div class="">
                                                <input id="slug" type="text" class="form-control" name="slug" autofocus value="@if( isset( $attribute ) ){{ $attribute->slug }}@else{{ old('slug') }}@endif" placeholder="Enter slug">
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
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="blog-left col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    {{ __('Attributes') }}
                                    <div class="d-flex align-items-center mt-2 justify-content-between">
                                        <form class="d-flex align-items-center col-md-5" action="{{route('attributes.index',)}}" method="get">
                                            <input name="search" size="40" id="search" value="{{request('search')}}" class="form-control" placeholder="Search attribute">
                                            <div>
                                                <button id="seachBtnAction" type="submit" class="btn btn-sm ml-2 btn-primary">Search</button>
                                            </div>
                                            <a href="{{route('attributes.index')}}" class="ml-2" title="Reset Search"><i class='fa fa-refresh'></i></a>
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
                                                <td>@sortablelink("name")</td>
                                                <td>@sortablelink("slug")</td>
                                                <td>@sortablelink("description")</td>
                                                <td>Terms</td>
                                                <td>@sortablelink("created_at","Created Date")</td>
                                                <td>@sortablelink("updated_at","Updated Date")</td>
                                                <td>Action</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($attributes as $attribute)
                                            <tr>
                                                <td><input type="checkbox" class="checkboxall" id="{{$attribute->id}}"></td>
                                                <td>{{ $attribute->name }}</td>
                                                <td>{{ $attribute->slug }}</td>
                                                <td>{{ $attribute->short_description }}</td>
                                                <td>
                                                    @php $len = count($attribute->terms); @endphp
                                                    @if($len > 0)
                                                    @foreach($attribute->terms as $k=> $term)
                                                    @if(($k == $len - 1))
                                                    {{$term->name}}
                                                    @else
                                                    {{$term->name}},
                                                    @endif
                                                    @endforeach
                                                    <br>
                                                    @endif
                                                    <a href="{{route('attributes.show',$attribute->id)}}">Configure terms</a>
                                                </td>
                                                <td>{{ getFormatDate($attribute->created_at) }}</td>
                                                <td>{{ getFormatDate($attribute->updated_at) }}</td>
                                                <td>
                                                    <a href="javascript:void(0)" class="edit-attribute text-primary" data-id="{{$attribute->id}}" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                                    <a href="javascript:void(0)" class="delete-attribute text-danger" data-id="{{$attribute->id}}" title="Delete"><i class="fa-solid fa-trash"></i></a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer clearfix">
                            <div class="d-flex justify-content-end">

                                    {{ $attributes->links() }}
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
        $(".edit-attribute").click(function() {
            var attribute_id = $(this).data('id');
            $.ajax({
                url: "{{ url('/') }}/attributes/" + attribute_id + "/edit",
                method: "GET",
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response) {
                    if (response) {
                        var attribute = response;
                        $(".add-edit").html("Edit attribute");
                        $("#edit_id").val(attribute.id);
                        $("#title").val(attribute.name);
                        $("#description").val(attribute.description);
                        $("#slug").val(attribute.slug);
                    }
                }
            });

        })

        $('body').on('click', '.delete-attribute', function(e) {
            e.preventDefault();
            if (confirm("Are you sure you want to delete this attribute?")) {
                var attribute_id = $(this).data('id');
                $.ajax({
                    url: "{{ url('/') }}/attributes/" + attribute_id,
                    method: "DELETE",
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function(response) {
                        toastr.success("attribute Deleted Successfully");
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
            alert("please select attribute")
            return
        }
        if (action == "") {
            alert("please select action")
            return
        }
        $.ajax({
            url: "{{ route('attributes.bulk_action') }}",
            method: "POST",
            data: {
                "selectedIds": selectedIds,
                "action": action,
            },
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
            },
            success: function(response) {
                toastr.success("Attributes Deleted Successfully");
                $(".tableData").load(document.URL + " .tableData");
            }
        });
    });
    // bulk action end
</script>
@endsection