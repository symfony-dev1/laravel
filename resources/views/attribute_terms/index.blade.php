@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="ml-2">{{ __("Product")}} {{$attribute->name}}</h1>
                <div class="back-btn bredcrumb">
                    <a href="{{route('products.index')}}">Products</a><i class="fas fa-chevron-right"></i><a href="{{route('attributes.index')}}">Attributes</a><i class="fas fa-chevron-right"></i><a href="#">{{$attribute->name}}</a>
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
                                    <span class="add-edit"> {{ __('Create') }}</span> {{ $attribute->name }}
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="{{ route('attribute_terms.store') }}" id="attributes" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" id="edit_id" name="edit_id" value="">
                                        <input type="hidden" id="attribute_id" name="attribute_id" value="{{$attribute->id}}">

                                        <div class="form-group col-md-12">
                                            <label for="title" class="col-form-label text-md-right">
                                                {{ __('Title') }}<span class="mandatory_fields">*</span>
                                            </label>
                                            <div class="">
                                                <input id="title" type="text" class="form-control" name="attribute_term_title" autofocus value="{{ old('attribute_term_title') }}" placeholder="Enter title">
                                                @if ($errors->has('attribute_term_title'))
                                                <span class="text-danger">{{ $errors->first('attribute_term_title') }}</span>
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
                                    {{ $attribute->name }} {{ __('Lists') }}
                                    <div class="d-flex align-items-center mt-2 justify-content-between">
                                        <form class="d-flex align-items-center col-md-5" action="{{route('attribute_terms.index',)}}" method="get">
                                            <input name="search" size="40" id="search" value="{{request('search')}}" class="form-control" placeholder="Search attribute term">
                                            <div>
                                                <button id="seachBtnAction" type="submit" class="btn btn-sm ml-2 btn-primary">Search</button>
                                            </div>
                                            <a href="{{route('attribute_terms.index')}}" class="ml-2" title="Reset Search"><i class='fa fa-refresh'></i></a>
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
                                                <td>@sortablelink("short_description","Description")</td>
                                                <td>@sortablelink("created_at","Created Date")</td>
                                                <td>@sortablelink("updated_at","Updated Date")</td>
                                                <td>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($terms as $term)
                                            <tr>
                                                <td><input type="checkbox" class="checkboxall" id="{{$term->id}}"></td>
                                                <td>{{ $term->name }}</td>
                                                <td>{{ $term->slug }}</td>
                                                <td>{{ $term->short_description }}</td>
                                                <td>{{ getFormatDate($term->created_at) }}</td>
                                                <td>{{ getFormatDate($term->updated_at) }}</td>
                                                <td>
                                                    <a href="javascript:void(0)" class="edit-attribute-term text-primary" data-id="{{$term->id}}" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                                    <a href="javascript:void(0)" class="delete-attribute-term text-danger" data-id="{{$term->id}}" title="Delete"><i class="fa-solid fa-trash"></i></a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer clearfix">
                                    <div class="d-flex justify-content-end">
                                        {{ $terms->links() }}
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
        $(".edit-attribute-term").click(function() {
            var attribute_term_id = $(this).data('id');
            $.ajax({
                url: "{{ url('/') }}/attribute_terms/" + attribute_term_id + "/edit",
                method: "GET",
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response) {
                    if (response) {
                        var attribute_term = response;
                        $(".add-edit").html("Edit");
                        $("#edit_id").val(attribute_term.id);
                        $("#title").val(attribute_term.name);
                        $("#description").val(attribute_term.short_description);
                        $("#slug").val(attribute_term.slug);
                    }
                }
            });

        })

        $('body').on('click', '.delete-attribute-term', function(e) {
            e.preventDefault();
            if (confirm("Are you sure you want to delete this attribute?")) {
                var attribute_term_id = $(this).data('id');
                $.ajax({
                    url: "{{ url('/') }}/attribute_terms/" + attribute_term_id,
                    method: "DELETE",
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function(response) {
                        toastr.success("Attribute term Deleted Successfully");
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
            alert("please select attribute term")
            return
        }
        if (action == "") {
            alert("please select action")
            return
        }
        $.ajax({
            url: "{{ route('attribute_terms.bulk_action') }}",
            method: "POST",
            data: {
                "selectedIds": selectedIds,
                "action": action,
            },
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
            },
            success: function(response) {
                toastr.success("Attribute term Deleted Successfully");
                $(".tableData").load(document.URL + " .tableData");
            }
        });
    });
    // bulk action endu
</script>
@endsection