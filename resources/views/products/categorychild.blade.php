@if($type == 'create')
@php $sp .= '&nbsp;&nbsp;&nbsp;' @endphp
@foreach ($categories as $category)
<div class="">
    <option value="{{ $category->id }}">{!! $sp !!}{{ $category->title }}</option>
</div>
@if( $category->childs->count() > 0)
@include('products.categorychild',['categories' => $category->childs, 'type' => 'create'])
@endif
@endforeach
@elseif($type == 'select')
@php $sp .= '&nbsp;&nbsp;&nbsp;' @endphp
@foreach ($categories as $category)
<div class="">
    {!! $sp !!}<input type="checkbox" value="{{ $category->id }}" name="categories[]" @if (isset($postcategory) && in_array($category->id, $postcategory)) checked @endif> {{ $category->title }} <span class="delete-category" data-id="{{ $category->id }}"><i class=" fa fa-minus icon-minus"></i></span>
</div>
@if( $category->childs->count() > 0)
@include('products.categorychild',['categories' => $category->childs, 'type' => 'select'])
@endif
@endforeach
@else
<ul class="c-sidebar-nav-dropdown-items show-account faq-list">
    @foreach ($categories as $category)
    <li class="{{ route('blog_category', $category->slug) == url()->current() ? 'active' : '' }}">
        <a href="{{ route('blog_category', $category->slug) }}" class="{{ route('blog_category', $category->slug) == url()->current() ? 'active-category' : '' }}" @if(!in_array($category->id, $category_id)) @if($category->childs->count() > 0) style=" pointer-events: none; cursor: default;" @else style="display: none" @endif @endif>{{ $category->name }}</a> @if( $category->childs->count() > 0) <a href="javascript:void(0)" class="indicator"><span class="fa fa-angle-down"></span></a> @endif
        @if( $category->childs->count() > 0)
        @include('products.categorychild',['categories' => $category->childs, 'type' => 'blog_front_view'])
        @endif
    </li>
    @endforeach
</ul>
@endif