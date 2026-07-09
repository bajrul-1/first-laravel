@extends('layouts.admin')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h3 class="fw-bold text-dark mb-1">Register New Business</h3>
        <p class="text-muted small">Step 1: Onboard the company profile into the master registry.</p>
    </div>
</div>

<div class="row">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card shadow-sm bg-white">
            <div class="card-body p-4 p-md-5">
                
                <form action="/company/store" method="POST">
                    @csrf 
                    <div class="mb-3">
                        <label for="name" class="form-label small fw-semibold text-secondary">Company / Business Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control py-2" id="name" name="name" required placeholder="e.g., Royal Bakery Group">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label small fw-semibold text-secondary">Corporate Email Address</label>
                        <input type="email" class="form-control py-2" id="email" name="email" placeholder="contact@bakery.com">
                    </div>
                    <div class="mb-4">
                        <label for="phone" class="form-label small fw-semibold text-secondary">Contact Number</label>
                        <input type="text" class="form-control py-2" id="phone" name="phone" placeholder="+91 xxxxx xxxxx">
                    </div>

                    <div class="row g-2">
                        <div class="col-6">
                            <button type="submit" class="btn btn-primary w-100 py-2 fw-medium">Register Business</button>
                        </div>
                        <div class="col-6">
                            <a href="/" class="btn btn-outline-secondary w-100 py-2">Cancel</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection