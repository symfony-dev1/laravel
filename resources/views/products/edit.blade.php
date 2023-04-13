@extends('layouts.app')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="ml-2">{{ __('Edit product') }}</h1>
                <div class="back-btn bredcrumb">
                    <a href="{{route('products.index')}}">Products</a><i class="fas fa-chevron-right"></i><a href="#">Edit product</a>
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
                    <form method="POST" action="{{ route('products.store') }}" id="products" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-9">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>
                                            @if (isset($product))
                                            {{ __('Edit Product') }}
                                            @else
                                            {{ __('Create Product') }}
                                            @endif
                                        </strong>
                                    </div>
                                    <div class="card-body">

                                        <div class="form-group col-md-12">
                                            <label for="title" class="col-form-label text-md-right">
                                                {{ __('Title') }} <span class="mandatory_fields">*</span>
                                            </label>
                                            <div class="">
                                                <input id="product_title" type="text" class="form-control" name="product_title" autofocus value="@if( isset( $product ) ){{ $product->title }}@else{{ old('product_title') }}@endif" placeholder="Enter product title" @if(!isset($product)) onchange="getslug()" @endif>
                                                <label for="product_slug" class="col-form-label text-md-right">Slug : </label><span class="product-slug">@if(isset($product)) @if($product->status == 3)<a href="http://localhost:3000/product/{{ $product->slug }}" target="_blank"> http://localhost:3000/product/{{ $product->slug }} </a> @else <a href="http://localhost:3000/product/{{ $product->slug }}" target="_blank"> http://localhost:3000/product/{{ $product->slug }} </a> @endif <span class="edit-button" onclick="editslug()">Edit</span> @endif</span>
                                                <span class="edit-slug-message"></span>
                                                <input type="hidden" name="product_slug" id="product-slug" value="@if(isset($product))  {{$product->slug}}  @endif">
                                                @if ($errors->has('product_title'))
                                                <span class="text-danger">{{ $errors->first('product_title') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="product_description" class="col-form-label text-md-right">
                                                {{ __('Product Description') }} <span class="mandatory_fields">*</span>
                                            </label>
                                            <div class="">
                                                <textarea id="product_description" class="form-control tinymce-editor" name="product_description"> {{ old('product_description') }} </textarea>
                                                <span id="ck_description" style="color: red"></span>
                                                @if ($errors->has('product_description'))
                                                <span class="text-danger">{{ $errors->first('product_description') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="product_description" class="col-form-label text-md-right">
                                                {{ __('Product Data') }} <span class="mandatory_fields">*</span>
                                            </label>
                                            <div class="">
                                                <div class="col-md-9">
                                                    <div class="card ">
                                                        <div class="card-body">
                                                            <ul class="nav nav-tabs" id="pills-tab" role="tablist">
                                                                <li class="nav-item general">
                                                                    <a class="nav-link" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="false">General</a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a class="nav-link " id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Inventory</a>
                                                                </li>
                                                                <li class="nav-item ">
                                                                    <a class="nav-link removeActive" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">Shipping</a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a class="nav-link removeActive" id="pills-attributes-tab" data-toggle="pill" href="#pills-attributes" role="tab" aria-controls="pills-attributes" aria-selected="false">Attributes</a>
                                                                </li>
                                                                <li class="nav-item variation " style="display: none;">
                                                                    <a class="nav-link removeActive" id="pills-variations-tab" data-toggle="pill" href="#pills-variations" role="tab" aria-controls="pills-variations" aria-selected="false">Variations</a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <select class="form-control" name="product_type" id="product_type">
                                                                        <option value="0">Simple Product</option>
                                                                        <option value="1">Variable Product</option>
                                                                    </select>
                                                                </li>
                                                            </ul>

                                                            <div class="tab-content" id="pills-tabContent">
                                                                <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                                                    <div class="card">
                                                                        <div class="card-body">
                                                                            <label class="mt-3 " for="price">Regular Price</label>
                                                                            <input class="form-control w-25 " type="text" name="price" value="{{ old('price') }}" id="price" placeholder="Regular Price">
                                                                            <label class="mt-3" for="sale_price">Sale Price</label>
                                                                            <span><a href="#" id="schedule"> &nbsp; Schedule</a></span>
                                                                            <span><a href="#" id="cancel" style="display:none"> &nbsp; Cancel Schedule</a></span>
                                                                            <input class="form-control w-25 " type="text" name="sale_price" value="{{ old('sale_price') }}" id="sale_price" placeholder="Sale Price">
                                                                            <div class="row" id="sale_dates" style="display: none">
                                                                                <div class="col">
                                                                                    <label class="mt-3 " for="start_date">Sale Start Date</label>
                                                                                    <input class="form-control" type="date" name="start_date" id="start_date">
                                                                                </div>
                                                                                <div class="col">
                                                                                    <label class="mt-3 " for="end_date">Sale End Date</label>
                                                                                    <input class="form-control" type="date" name="end_date" id="end_date">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="tab-pane fade " id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                                                    <div class="card">
                                                                        <div class="card-body">
                                                                            <label class="mt-3 " for="sku">SKU</label>
                                                                            <input class="form-control w-25 " type="text" name="sku" value="{{ old('sku') }}" id="sku" placeholder="SKU.">
                                                                            <label class="mt-3" for="stock">Stock Quantity</label>
                                                                            <input class="form-control w-25 " type="text" name="stock" value="{{ old('stock') }}" id="stock" placeholder="Stock">
                                                                            <label class="mt-3" for="back_orders">Allow backorders?</label>
                                                                            <select class="form-control  w-25" name="allow_backorders" id="allow_backorders">
                                                                                <option value="1">Yes</option>
                                                                                <option value="0">No</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="tab-pane fade removeActive" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                                                                    <div class="card">
                                                                        <div class="card-body">
                                                                            <label class="mt-3 " for="weight">Weight(Kg.)</label>
                                                                            <input class="form-control w-25 " type="text" name="weight" value="{{ old('weight') }}" id="weight" placeholder="Weight">
                                                                            <label class="mt-3" for="stock">Dimensions(cm)</label>
                                                                            <div class="row w-50">
                                                                                <div class="col-md-4">
                                                                                    <input class="form-control" type="text" name="length" value="{{ old('length') }}" id="length" placeholder="Length">
                                                                                </div>
                                                                                <div class="col-md-4">
                                                                                    <input class="form-control" type="text" name="width" value="{{ old('width') }}" id="width" placeholder="Width">
                                                                                </div>
                                                                                <div class="col-md-4">
                                                                                    <input class="form-control" type="text" name="height" value="{{ old('height') }}" id="height" placeholder="Height">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="tab-pane fade removeActive" id="pills-attributes" role="tabpanel" aria-labelledby="pills-attributes-tab">
                                                                    <div class="card">
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="col-md-8">
                                                                                    <label for="attribute">Attributes</label>
                                                                                    <select class="form-control" name="attribute" id="attribute">
                                                                                        <option value="">--Select Attribute--</option>
                                                                                        @foreach ($attributes as $attribute)
                                                                                        <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                    <div id="terms" class="mt-3">
                                                                                    </div>
                                                                                    {{-- <input type="hidden" name="all_attribute_terms" id="all_attribute_terms">                          --}}
                                                                                </div>
                                                                                <div class="col-md-4 mt-4">
                                                                                    <button class="btn btn-primary addAttribute">Add</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <div class="tab-pane fade removeActive" id="pills-variations" role="tabpanel" aria-labelledby="pills-variations-tab">
                                                                    <div class="card">
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="col">
                                                                                    {{-- <button class="btn btn-primary addVariation">Update Attribute Variations</button> --}}
                                                                                    <span id="noAttError" class="text text-danger"></span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row mt-3">
                                                                                <label for="make_variations">Select Variation: </label>
                                                                                <select class="form-control" name="make_variations" id="make_variations">
                                                                                    <option value="">--Select Variation--</option>
                                                                                </select>
                                                                                <button class=" mt-2 btn btn-primary addVariant">add</button>
                                                                            </div>
                                                                            <div id="variants">

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="product_short_description" class="col-form-label text-md-right">
                                                {{ __('Product Short Description') }} <span class="mandatory_fields">*</span>
                                            </label>
                                            <div class="">
                                                <textarea id="product_short_description" class="form-control tinymce-editor" name="product_short_description"> {{ old('product_short_description') }} </textarea>
                                                <span id="ck_description" style="color: red"></span>
                                                @if ($errors->has('product_short_description'))
                                                <span class="text-danger">{{ $errors->first('product_short_description') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="blog-left col-md-3">
                                <div class="card">
                                    <div class="form-group col-md-12">
                                        <label for="status" class="col-form-label text-md-right">
                                            {{ __('Status') }}
                                        </label>
                                        <div class="">
                                            <select name="status" id="status" class="form-control">
                                                <option value="1" @if(isset($product) && $product->status == 1) selected @endif>Draft</option>
                                                <option value="2" @if(isset($product) && $product->status == 2) selected @endif>Pending Review</option>
                                                <option value="3" @if(isset($product) && $product->status == 3) selected @endif>Published</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-primary mx-1" id="submitBtn"> {{ __('Save') }} </button>
                                    </div>
                                </div>
                                <div class="card my-3">
                                    <div class="form-group col-md-12">
                                        <label for="category" class="col-form-label text-md-right">
                                            {{ __('Product Categories') }} <span class="mandatory_fields">*</span>
                                        </label>
                                        <div class="form-category">
                                            <div class="category">
                                                @if($categories->isEmpty())
                                                {{ __('Add Category') }}
                                                @else
                                                @foreach ($categories as $category)
                                                @php $sp = ''; @endphp
                                                <div class="">
                                                    <input type="checkbox" value="{{ $category->id }}" name="categories[]" @if (isset($productcategory) && in_array($category->id, $productcategory)) checked @endif> {{ $category->title }} <span class="delete-category" data-id="{{ $category->id }}" title="Delete"><i class="fa fa-minus icon-minus"></i></span>
                                                </div>
                                                @if( $category->childs->count() > 0)
                                                @include('products.categorychild',['categories' => $category->childs, 'sp' => $sp, 'type' => 'select'])
                                                @endif
                                                @endforeach
                                                @endif
                                            </div>
                                            <span id="ck_description" style="color: red"></span>
                                        </div>
                                        <div class="text-center add-category-link">
                                            <a href="javascript:void(0)"><i class="fa fa-plus"></i> Add product category</a>
                                        </div>
                                        <div class="form-group category-name" style="display: none">
                                            <input class="form-control" id='category-title' placeholder="Category title">
                                            @if ($errors->has('category_title'))
                                            <span class="text-danger">{{ $errors->first('category_title') }}</span>
                                            @endif
                                        </div>
                                        <div class="form-group category-list" style="display: none">
                                            <select id="parent-category" class="form-control">
                                                <div class="">
                                                    <option value="">Parent Category</option>
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
                                        <div class="add-category-btn" style="display: none">
                                            <input type="button" class="btn btn-primary mx-1" id="add-category-btn" value="Add">
                                        </div>
                                        @if ($errors->has('categories'))
                                        <span class="text-danger">{{ $errors->first('categories') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="card my-3">
                                    <div class="form-group col-md-12">
                                        <label for="brand" class="col-form-label text-md-right">
                                            {{ __('Brands') }} <span class="mandatory_fields">*</span>
                                        </label>
                                        <div class="form-brand">
                                            <div class="brand">
                                                @if($brands->isEmpty())
                                                {{ __('Add Brand') }}
                                                @else
                                                @foreach ($brands as $brand)
                                                @php $sp = ''; @endphp
                                                <div class="">
                                                    <input type="checkbox" value="{{ $brand->id }}" name="brands[]" @if (isset($productbrand) && in_array($brand->id, $productbrand)) checked @endif> {{ $brand->title }} <span class="delete-brand" data-id="{{ $brand->id }}" title="Delete"><i class="fa fa-minus icon-minus"></i></span>
                                                </div>
                                                @if( $brand->childs->count() > 0)
                                                @include('products.brandchild',['brands' => $brand->childs, 'sp' => $sp, 'type' => 'select'])
                                                @endif
                                                @endforeach
                                                @endif
                                            </div>
                                            <span id="ck_description" style="color: red"></span>
                                        </div>
                                        <div class="text-center add-brand-link">
                                            <a href="javascript:void(0)"><i class="fa fa-plus"></i> Add product brand</a>
                                        </div>
                                        <div class="form-group brand-name" style="display: none">
                                            <input class="form-control" id='brand-title' placeholder="brand title">
                                            @if ($errors->has('brand_title'))
                                            <span class="text-danger">{{ $errors->first('brand_title') }}</span>
                                            @endif
                                        </div>
                                        <div class="form-group brand-list" style="display: none">
                                            <select id="parent-brand" class="form-control">
                                                <div class="">
                                                    <option value="">Parent brand</option>
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
                                        <div class="add-brand-btn" style="display: none">
                                            <input type="button" class="btn btn-primary mx-1" id="add-brand-btn" value="Add">
                                        </div>
                                        @if ($errors->has('brands'))
                                        <span class="text-danger">{{ $errors->first('brands') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="col-md-12">
                                        <label for="blog_image" class="col-form-label text-md-right">
                                            {{ __('Product Image') }}<span class="mandatory_fields">*</span>
                                        </label>
                                        <div class="form-image">
                                            <a href="javascript:void(0)" class="image_link" @if (isset($product->product_image)) style="display: none" @endif>{{ __('Add featured image') }}</a>
                                            <input type="file" style="display: none" name="image" class="inputfile" accept="image/*">
                                            @if(isset($product->product_image))
                                            <input type="hidden" value="{{ $product->product_image }}" name='image' class="product_image">
                                            <img class="add-post-img" src="{{ asset('/uploads/'.$product->product_image) }}">
                                            <div class='imgalt'>
                                                <label for="alt" class="col-form-label text-md-right">
                                                    {{ __('Alt') }}
                                                </label>
                                                <input type="text" name='imgalt' class="form-control" @if(isset($product)) value="{{ $product->image_alt }}" @endif placeholder="Enter alt for image">
                                            </div>
                                            <div class='caption'>
                                                <label for="caption" class="col-form-label text-md-right">
                                                    {{ __('Caption') }}
                                                </label>
                                                <textarea name='caption' class="form-control" placeholder="Enter caption for image">@if(isset($product)){{$product->caption}}@endif</textarea>
                                            </div>
                                            @else
                                            <img class="add-post-img" style="display: none">
                                            <div class='imgalt' style="display: none;">
                                                <label for="alt" class="col-form-label text-md-right">
                                                    {{ __('Alt') }}
                                                </label>
                                                <input type="text" name='imgalt' class="form-control" value="{{ old('imgalt') }}" placeholder="Enter alt for image">
                                            </div>
                                            <div class='caption' style="display: none;">
                                                <label for="caption" class="col-form-label text-md-right">
                                                    {{ __('Caption') }}
                                                </label>
                                                <textarea name='caption' class="form-control" placeholder="Enter caption for image">{{ old('caption') }}</textarea>
                                            </div>
                                            @endif
                                            <p @if(!isset($product->product_image)) style="display: none" @endif class="remove_link"><a href="javascript:void(0)">{{ __('Replace featured image') }}</a></p>
                                        </div>
                                        @if ($errors->has('image'))
                                        <span class="text-danger">{{ $errors->first('image') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="card my-3">
                                    <div class="form-group col-md-12">
                                        <label for="tag" class="col-form-label text-md-right">
                                            {{ __('Tags') }} <span class="mandatory_fields">*</span>
                                        </label>
                                        <div class="form-tag">
                                            <div class="tag">
                                                @if($tags->isEmpty())
                                                {{ __('Add tag') }}
                                                @else
                                                @foreach ($tags as $tag)
                                                @php $sp = ''; @endphp
                                                <div class="">
                                                    <input type="checkbox" value="{{ $tag->id }}" name="tags[]" @if (isset($producttag) && in_array($tag->id, $producttag)) checked @endif> {{ $tag->title }} <span class="delete-tag" data-id="{{ $tag->id }}" title="Delete"><i class="fa fa-minus icon-minus"></i></span>
                                                </div>
                                                @endforeach
                                                @endif
                                            </div>
                                            <span id="ck_description" style="color: red"></span>
                                        </div>
                                        <div class="text-center add-tag-link">
                                            <a href="javascript:void(0)"><i class="fa fa-plus"></i> Add product tag</a>
                                        </div>
                                        <div class="form-group tag-name" style="display: none">
                                            <input class="form-control" id='tag-title' placeholder="tag title">
                                            @if ($errors->has('tag_title'))
                                            <span class="text-danger">{{ $errors->first('tag_title') }}</span>
                                            @endif
                                        </div>
                                        <div class="add-tag-btn" style="display: none">
                                            <input type="button" class="btn btn-primary mx-1" id="add-tag-btn" value="Add">
                                        </div>
                                        @if ($errors->has('tags'))
                                        <span class="text-danger">{{ $errors->first('tags') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
@endsection


@section('scripts')
<script src="{{ asset('vendor/tinymce/tinymce.js') }}"></script>
<script src="{{ asset('vendor/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('vendor/products/products.js') }}"></script>

<script type="text/javascript">
    // SummerNote Editor
    // $('#body').summernote({
    //     placeholder: 'Blog Body',
    //     tabsize: 2,
    //     height: 350,
    //     toolbar: [
    //         // ['cleaner',['cleaner']],
    //         ['custom',['pageTemplates','blocks']],
    //         ['style1', ['style']],
    //         ['style2', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript']],
    //         ['fontsize', ['fontsize', 'lineHeights']],
    //         ['fontname', ['fontname']],
    //         ['color', ['color']],
    //         ['para', ['ul', 'ol', 'paragraph', 'height']],
    //         ['table', ['table']],
    //         ['insert', ['link', 'picture', 'video', 'hr']],
    //         ['view', ['fullscreen', 'codeview', 'help']],

    //     ],
    //     popover: {
    //     image: [['resize', ['resizeFull', 'resizeHalf', 'resizeQuarter', 'resizeNone']], ['float', ['floatLeft', 'floatRight', 'floatNone']], ['remove', ['removeMedia']]],
    //     link: [['link', ['linkDialogShow', 'unlink']]],
    //     table: [['add', ['addRowDown', 'addRowUp', 'addColLeft', 'addColRight']], ['delete', ['deleteRow', 'deleteCol', 'deleteTable']]],
    //     air: [['color', ['color']], ['font', ['bold', 'underline', 'clear']], ['para', ['ul', 'paragraph']], ['table', ['table']], ['insert', ['link', 'picture']], ['view', ['fullscreen', 'codeview']]]
    //     },
    //     textareaAutoSync: true,
    //     popatmouse:true,
    //     editing:true,
    //     blockquoteBreakingLevel: 2,
    //     tabDisable: true,
    //     codeviewFilter: false,
    //     codeviewIframeFilter: true,
    //     spellCheck: true,
    // });
    tinymce.init({
        selector: 'textarea.tinymce-editor',
        plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons',
        imagetools_cors_hosts: ['picsum.photos'],
        menubar: 'file edit view insert format tools table help',
        toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
        toolbar_sticky: true,
        autosave_ask_before_unload: true,
        autosave_interval: '30s',
        autosave_prefix: '{path}{query}-{id}-',
        autosave_restore_when_empty: false,
        autosave_retention: '2m',
        image_advtab: true,
        image_class_list: [{
                title: 'None',
                value: ''
            },
            {
                title: 'Some class',
                value: 'class-name'
            }
        ],
        importcss_append: true,
        qusetup: function(editor) {
            editor.ui.registry.addContextToolbar('imageselection', {
                predicate: function(node) {
                    return node.nodeName === 'P';
                },
                items: 'quicklink',
                position: 'node'
            });
        },
        file_picker_callback: (cb, value, meta) => {
            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');

            input.addEventListener('change', (e) => {
                const file = e.target.files[0];

                const reader = new FileReader();
                reader.addEventListener('load', () => {
                    /*
                    Note: Now we need to register the blob in TinyMCEs image blob
                    registry. In the next release this part hopefully won't be
                    necessary, as we are looking to handle it internally.
                    */
                    const id = 'blobid' + (new Date()).getTime();
                    const blobCache = tinymce.activeEditor.editorUpload.blobCache;
                    const base64 = reader.result.split(',')[1];
                    const blobInfo = blobCache.create(id, file, base64);
                    blobCache.add(blobInfo);

                    /* call the callback and populate the Title field with the file name */
                    cb(blobInfo.blobUri(), {
                        title: file.name
                    });
                });
                reader.readAsDataURL(file);
            });

            input.click();
        },
        templates: [{
                title: 'New Table',
                description: 'creates a new table',
                content: '<div class="mceTmpl"><table width="98%%"  border="0" cellspacing="0" cellpadding="0"><tr><th scope="col"> </th><th scope="col"> </th></tr><tr><td> </td><td> </td></tr></table></div>'
            },
            {
                title: 'Starting my story',
                description: 'A cure for writers block',
                content: 'Once upon a time...'
            },
            {
                title: 'New list with dates',
                description: 'New List with dates',
                content: '<div class="mceTmpl"><span class="cdate">cdate</span><br /><span class="mdate">mdate</span><h2>My List</h2><ul><li></li><li></li></ul></div>'
            }
        ],
        template_cdate_format: '[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]',
        template_mdate_format: '[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]',
        height: 600,
        image_caption: true,
        quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
        noneditable_noneditable_class: 'mceNonEditable',
        toolbar_mode: 'sliding',
        browser_spellcheck: true,
        contextmenu: 'link image table paste',
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
    });
    $("#formReset").click(function() {
        $('#product_title').val("");
        $('#image').val("");
        $('#excerpt').summernote('reset');
        $('#body').summernote('reset');
    });

    $('.image_link').on('click', function() {
        $('.inputfile').trigger("click");
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('.add-post-img').show();
                $('.add-post-img').attr('src', e.target.result);
                $('.image_link').hide();
                $('.imgalt').show();
                $('.caption').show();
                $('.remove_link').show();
                $('.hr').show();
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $(".inputfile").change(function() {
        readURL(this);
    });

    $(".remove_link").on('click', function() {
        $('.inputfile').val('');
        $('.add-post-img').hide();
        $('.image_link').show();
        $('.imgalt').hide();
        $('.caption').hide();
        $('.remove_link').hide();
        $('.hr').hide();
    });

    $('.add-category-link').on('click', function() {
        $('.category-name').show();
        $('.category-list').show();
        $('.add-category-btn').show();
    });

    $('.add-category-btn').on('click', function() {
        var category_title = $('#category-title').val();
        var parent_id = $('#parent-category').val();
        $.ajax({
            url: "{{ route('categories.store') }}",
            method: "POST",
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
            },
            data: {
                'category_title': category_title,
                'parent_id': parent_id,
            },
            success: function(response) {
                toastr.success("Category added sucessfully");
                $(".category").load(document.URL + " .category");
                $('.category-name').hide();
                $('.category-list').hide();
                $('.add-category-btn').hide();
            }
        });
    });

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
                    $(".category").load(document.URL + " .category");
                }
            });
        }
    });


    $('.add-brand-link').on('click', function() {
        $('.brand-name').show();
        $('.brand-list').show();
        $('.add-brand-btn').show();
    });

    $('.add-brand-btn').on('click', function() {
        var brand_title = $('#brand-title').val();
        var parent_id = $('#parent-brand').val();
        $.ajax({
            url: "{{ route('brands.store') }}",
            method: "POST",
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
            },
            data: {
                'brand_title': brand_title,
                'parent_id': parent_id,
            },
            success: function(response) {
                toastr.success("Brand added sucessfully");

                $(".brand").load(document.URL + " .brand");
                $('.brand-name').hide();
                $('.brand-list').hide();
                $('.add-brand-btn').hide();
            }
        });
    });

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
                    toastr.success("Brand deleted sucessfully");
                    $(".brand").load(document.URL + " .brand");
                }
            });
        }
    });

    $('.add-tag-link').on('click', function() {
        $('.tag-name').show();
        $('.tag-list').show();
        $('.add-tag-btn').show();
    });

    $('.add-tag-btn').on('click', function() {
        var tag_title = $('#tag-title').val();
        $.ajax({
            url: "{{ route('tags.store') }}",
            method: "POST",
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
            },
            data: {
                'tag_title': tag_title,
            },
            success: function(response) {
                toastr.success("tag added sucessfully");

                $(".tag").load(document.URL + " .tag");
                $('.tag-name').hide();
                $('.tag-list').hide();
                $('.add-tag-btn').hide();
            }
        });
    });

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
                data: {
                    'tag_id': tag_id,
                },
                success: function(response) {
                    toastr.success("tag deleted sucessfully");
                    $(".tag").load(document.URL + " .tag");
                }
            });
        }
    });

    function getslug() {
        var product_title = $('#product_title').val();
        $.ajax({
            url: "{{ route('products.create_product_slug') }}",
            type: "POST",
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
            },
            data: {
                product_title: product_title,
            },
            success: function(response) {
                $("#product-slug").val(response);
                var html = window.location + '/preview/' + response + ' @if(!isset($product)) <span class="edit-button" onclick="editslug()">Edit</span> @endif';
                $(".product-slug").html(html);
            }
        });
    }

    function editslug() {
        var blog_slug = $('.product-slug').text();
        var explodeslug = $('.product-slug').text().split('/');
        var lastelement = $(explodeslug).get(-1);
        var slug = lastelement.split(" ");
        var html = window.location + '/preview/ <input name="edit_slug" id="edit-slug" value="' + slug[0] + '"> <span onclick="updateslug()" class="edit-button">Update</span> <span  onclick="cancelslug()" class="edit-button">Cancel</span>';
        $(".product-slug").html(html);

    }

    function updateslug() {
        var update_slug = $('#edit-slug').val();
        $.ajax({
            url: "{{ route('products.update_product_slug') }}",
            type: "POST",
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
            },
            data: {
                update_slug: update_slug,
            },
            success: function(response) {
                if (response == 'success') {
                    $("#product-slug").val(update_slug);
                    var html = window.location + '/preview/' + update_slug + ' <span class="edit-button" onclick="editslug()">Edit</span>';
                    $(".product-slug").html(html);
                    $('.edit-slug-message').removeClass('error-meassage');
                    $('.edit-slug-message').addClass('success-meassage');
                    $('.edit-slug-message').text('Slug successfully updated');
                } else {
                    var html = window.location + '/view/ <input  name="edit_slug" id="edit-slug" value="' + update_slug + '"> <span onclick="updateslug()" class="edit-button">Update</span> <span onclick="cancelslug()" class="edit-button">Cancel</span>';
                    $(".product-slug").html(html);
                    $('.edit-slug-message').removeClass('success-meassage');
                    $('.edit-slug-message').addClass('error-meassage');
                    $('.edit-slug-message').text('This slug already exists.');
                    getslug();
                }
            }
        });
    }
</script>
@endsection