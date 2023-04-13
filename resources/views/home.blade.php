@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ __('Dashboard') }}</h1>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid px-4">
        <!-- <div class="row">
            <div class="col-lg-12">
               
                <div class="card">
                    <div class="card-body">
                        <p class="card-text">
                            {{ __('You are logged in!') }}
                        </p>
                    </div>
                </div>
            </div>
        </div> -->
        <div class="row">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header  p-3 pt-2">
                        <div class="pt-2 pb-2 px-2  m-1  bg-gradient-danger shadow-danger text-center border-radius-xl mt-n4 position-absolute dash-label">
                            <i class="material-icons opacity-10 ">Users</i>
                        </div>
                        <div class="text-end pt-4">
                            <p class="text-sm mb-0 text-capitalize">Today's Money</p>
                            <h4 class="mb-0">$53k</h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+55% </span>than last week</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div class="pt-2 pb-2 px-2  m-1  bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute dash-label">
                            <i class="material-icons opacity-10">Products</i>
                        </div>
                        <div class="text-end pt-4">
                            <p class="text-sm mb-0 text-capitalize">Today's Users</p>
                            <h4 class="mb-0">2,300</h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+3% </span>than last month</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div class="pt-2 pb-2 px-2  m-1  bg-gradient-success shadow-success text-center   border-radius-xl mt-n4 position-absolute dash-label">
                            <i class="material-icons opacity-10">Orders</i>
                        </div>
                        <div class="text-end pt-4">
                            <p class="text-sm mb-0 text-capitalize">New Clients</p>
                            <h4 class="mb-0">3,462</h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <p class="mb-0"><span class="text-danger text-sm font-weight-bolder">-2%</span> than yesterday</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div class="pt-2 pb-2 px-2  m-1  bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute dash-label">
                            <i class="material-icons opacity-10">Categories</i>
                        </div>
                        <div class="text-end pt-4">
                            <p class="text-sm mb-0 text-capitalize">Sales</p>
                            <h4 class="mb-0">$103,430</h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+5% </span>than yesterday</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
@endsection