@if($type == 'create')
@php $sp .= '&nbsp;&nbsp;&nbsp;' @endphp
@foreach ($brands as $brand)
<div class="">
    <option value="{{ $brand->id }}">{!! $sp !!}{{ $brand->title }}</option>
</div>
@if( $brand->childs->count() > 0)
@include('products.brandchild',['brands' => $brand->childs, 'type' => 'create'])
@endif
@endforeach
@elseif($type == 'select')
@php $sp .= '&nbsp;&nbsp;&nbsp;' @endphp
@foreach ($brands as $brand)
<div class="">
    {!! $sp !!}<input type="checkbox" value="{{ $brand->id }}" name="brands[]" @if (isset($productbrand) && in_array($brand->id, $productbrand)) checked @endif> {{ $brand->title }} <span class="delete-brand" data-id="{{ $brand->id }}"><i class=" fa fa-minus icon-minus"></i></span>
</div>
@if( $brand->childs->count() > 0)
@include('products.brandchild',['brands' => $brand->childs, 'type' => 'select'])
@endif
@endforeach
@else
<ul class="c-sidebar-nav-dropdown-items show-account faq-list">
    @foreach ($brands as $brand)
    <li class="{{ route('blog_brand', $brand->slug) == url()->current() ? 'active' : '' }}">
        <a href="{{ route('blog_brand', $brand->slug) }}" class="{{ route('blog_brand', $brand->slug) == url()->current() ? 'active-brand' : '' }}" @if(!in_array($brand->id, $brand_id)) @if($brand->childs->count() > 0) style=" pointer-events: none; cursor: default;" @else style="display: none" @endif @endif>{{ $brand->name }}</a> @if( $brand->childs->count() > 0) <a href="javascript:void(0)" class="indicator"><span class="fa fa-angle-down"></span></a> @endif
        @if( $brand->childs->count() > 0)
        @include('products.brandchild',['brands' => $brand->childs, 'type' => 'blog_front_view'])
        @endif
    </li>
    @endforeach
</ul>
@endif