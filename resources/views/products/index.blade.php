    @extends('layouts.app')

    @section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="ml-2">{{ __('Products') }}</h1>
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
                            <!-- {{ __('Products') }} -->
                            <div class="justify-content-between">
                                <div>
                                    {{ __('Products') }}
                                </div>
                                <div class="d-flex align-items-center mt-2 justify-content-between">
                                    <form class="d-flex align-items-center col-md-5 px-0" action="{{route('products.index',)}}" method="get">
                                        <select name="stock_action_input" id="stock_action_input" class="form-control">
                                            <option value="">Stock</option>
                                            <option value="1" class="text-success" {{ request()->input('stock_action_input') == '1' ? 'selected' : '' }}>IN STOCK</option>
                                            <option value="2" class="text-danger" {{ request()->input('stock_action_input') == '2' ? 'selected' : '' }}>OUT OF STOCK</option>
                                            <option value="3" class="text-info" {{ request()->input('stock_action_input') == '3' ? 'selected' : '' }}>ON BACK OREDER </option>
                                        </select>
                                        <input name="search" value="{{ request()->input('search')}}" size="50" id="search" class="form-control ml-2" placeholder="Search product">
                                        <button id="seachBtnAction" type="submit" class="btn btn-sm ml-2">Search</button>
                                        <a href="{{route('products.index')}}" class="ml-2" title="Reset Search"><i class='fa fa-refresh'></i></a>
                                    </form>
                                    <div class="col-md-5 d-flex">
                                        <select name="bulk_action_input" id="bulk_action_input" class="form-control ml-2 ">
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
                                        <td>Image</td>
                                        <td>@sortablelink("title")</td>
                                        <!-- <td>Stock</td> -->
                                        <td>@sortablelink("stock", "Stock")</td>

                                        <td>@sortablelink("price")</td>

                                        <td>Categories </td>
                                        <td>Brands</td>
                                        <td>Tags</td>
                                        <td>@sortablelink("created_at","Created Date")</td>
                                        <td>@sortablelink("updated_at","Updated Date")</td>
                                        <td>Action</td>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                    <?=
                                    $catDisplay = "";
                                    if (count($product->categories) > 0) {
                                        foreach ($product->categories as $k => $category) {
                                            if ($k == 0) {
                                                $catDisplay .=  $category->title;
                                            } else {
                                                $catDisplay .= ", " . $category->title;
                                            }
                                        }
                                    }

                                    $tagDisplay = "";
                                    if (count($product->tags) > 0) {
                                        foreach ($product->tags as $k => $tag) {
                                            if ($k == 0) {
                                                $tagDisplay .=  $tag->title;
                                            } else {
                                                $tagDisplay .= ", " . $tag->title;
                                            }
                                        }
                                    }

                                    $brandDisplay = "";
                                    if (count($product->brands) > 0) {
                                        foreach ($product->brands as $k => $brand) {
                                            if ($k == 0) {
                                                $brandDisplay .=  $brand->title;
                                            } else {
                                                $brandDisplay .= ", " . $brand->title;
                                            }
                                        }
                                    }
                                    ?>
                                    <tr>
                                        <td><input type="checkbox" class="checkboxall" id="{{$product->id}}"></td>
                                        <td><img src="{{url('/')}}/uploads/products/{{$product->product_image}}" weight="50" height="50"></td>
                                        <td>{{ $product->title }}</td>
                                        <td> @if($product->stock_status==1)
                                            <span class='text text-success'>In stock </span> ({{$product->stock}})
                                            @elseif($product->stock_status==2)
                                            <span class='text text-danger'> Out of stock </span> ({{$product->stock}})
                                            @elseif($product->stock_status==3)
                                            <span class='text text-info'>Back order </span> ({{$product->stock}})
                                            @endif
                                        </td>
                                        <td>{{ $product->price }}</td>
                                        <td>{{ $catDisplay }}</td>
                                        <td>{{ $brandDisplay }}</td>

                                        <td>{{$tagDisplay}}</td>
                                        <td>{{ getFormatDate($product->created_at) }}</td>
                                        <td>{{ getFormatDate($product->updated_at) }}</td>
                                        <td>
                                            <a href="{{route('products.edit',$product->id)}}" class="edit-product text-primary" data-id="{{$product->id}}" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                            <a href="javascript:void(0)" class="delete-product text-danger" data-id="{{$product->id}}" title="Delete"><i class="fa-solid fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer clearfix">
                            <div class="d-flex justify-content-end">
                                {{ $products->links() }}
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
    <script>
        $(document).ready(function() {
            $("#edit_id").val("");
            $(".edit-product").click(function() {
                var product_id = $(this).data('id');
                $.ajax({
                    url: "{{ url('/') }}/products/" + product_id + "/edit",
                    method: "GET",
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function(response) {
                        if (response) {
                            var product = response;
                            $(".add-edit").html("Edit product");
                            $("#edit_id").val(product.id);
                            $("#title").val(product.name);
                            $("#description").val(product.description);
                            $("#slug").val(product.slug);
                        }
                    }
                });

            })

            $('body').on('click', '.delete-product', function(e) {
                e.preventDefault();
                if (confirm("Are you sure you want to delete this product?")) {
                    var product_id = $(this).data('id');
                    $.ajax({
                        url: "{{ url('/') }}/products/" + product_id,
                        method: "DELETE",
                        headers: {
                            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                        },
                        success: function(response) {
                            toastr.success("product Deleted Successfully");
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
                alert("please select product")
                return
            }
            if (action == "") {
                alert("please select action")
                return
            }
            $.ajax({
                url: "{{ route('products.bulk_action') }}",
                method: "POST",
                data: {
                    "selectedIds": selectedIds,
                    "action": action,
                },
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response) {
                    toastr.success("Products Deleted Successfully");
                    $(".tableData").load(document.URL + " .tableData");
                }
            });
        });
        // bulk action end
    </script>

    @endsection