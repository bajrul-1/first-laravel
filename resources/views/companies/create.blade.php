@extends('app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="card-title mb-0">🏢 নতুন বেকারি/কোম্পানি যুক্ত করুন</h5>
            </div>
            <div class="card-body p-4">
                
                <form action="/company/store" method="POST">
                    @csrf 

                    <div class="mb-3">
                        <label for="name" class="form-label font-weight-bold">কোম্পানির নাম <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required placeholder="যেমন: আল-মদিনা বেকারি">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">ইমেইল ঠিকানা</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="company@example.com">
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">মোবাইল নম্বর</label>
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="017xxxxxxxx">
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-success py-2 fw-bold">সংরক্ষণ করুন (Save Company)</button>
                        <a href="/" class="btn btn-light py-2">ফিরে যান</a>
                    </div>
                </form>
                </div>
        </div>
    </div>
</div>
@endsection