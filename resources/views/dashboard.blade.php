@extends('app')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        🎉 <strong>Success!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row align-items-center mb-4">
    <div class="col-md-8">
        <h2>📊 Super Admin Dashboard</h2>
        <p class="text-muted">আপনার এন্টারপ্রাইজ প্ল্যাটফর্মের রিয়েল-টাইম ওভারভিউ।</p>
    </div>
    <div class="col-md-4 text-md-end">
        <a href="/company/create" class="btn btn-primary fw-bold shadow-sm py-2">+ নতুন কোম্পানি যোগ করুন</a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 bg-primary text-white mb-3">
            <div class="card-body py-4">
                <h5 class="card-title">মোট সক্রিয় কোম্পানি</h5>
                <h2 class="display-6 fw-bold">০</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 bg-success text-white mb-3">
            <div class="card-body py-4">
                <h5 class="card-title">মোট সাবস্ক্রিপশন আয়</h5>
                <h2 class="display-6 fw-bold">₹0.00</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 bg-danger text-white mb-3">
            <div class="card-body py-4">
                <h5 class="card-title">প্ল্যাটফর্মের নিজস্ব খরচ</h5>
                <h2 class="display-6 fw-bold">₹0.00</h2>
            </div>
        </div>
    </div>
</div>
@endsection