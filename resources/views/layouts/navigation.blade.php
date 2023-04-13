<!-- Sidebar -->
<div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
            <a href="{{ route('profile.show') }}" class="d-block">{{ Auth::user()->name }}</a>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
                <a href="{{ route('home') }}" class="nav-link">
                    <i class="nav-icon fas fa-th"></i>
                    <p>
                        {{ __('Dashboard') }}
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('users.index') }}" class="nav-link">
                    <i class="nav-icon fas fa-users"></i>
                    <p>
                        {{ __('Users') }}
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('roles.index') }}" class="nav-link">
                    <i class="nav-icon fas fa-list"></i>
                    <p>
                        {{ __('Role & Permissions') }}
                    </p>
                </a>
            </li>


            <li class="nav-item">
                <a href="{{ route('about') }}" class="nav-link">
                    <i class="nav-icon far fa-address-card"></i>
                    <p>
                        {{ __('About us') }}
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-list nav-icon"></i>
                    <p>Products
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview" style="display: none;">
                    <li class="nav-item">
                        <a href="{{ route('products.index') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>All Products</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('products.create') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Add New</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('categories.index')}}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Categories</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('tags.index')}}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Tags</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('attributes.index')}}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Attributes</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('brands.index')}}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Brands</p>
                        </a>
                    </li>
                </ul>
            </li>


        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->