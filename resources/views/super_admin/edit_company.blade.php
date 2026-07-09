@extends('layouts.admin')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <a href="/companies" class="btn btn-sm btn-outline-secondary mb-3">&larr; Back to Directory</a>
        <h3 class="fw-bold text-dark mb-1">Modify Enterprise Context</h3>
        <p class="text-muted small">Update official registration metadata for <strong>{{ $company->name }}</strong>.</p>
    </div>
</div>

<div class="row">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card shadow-sm bg-white">
            <div class="card-body p-4 p-md-5">
                
                <form action="/company/update/{{ $company->id }}" method="POST">
                    @csrf 
                    <div class="mb-3">
                        <label for="name" class="form-label small fw-semibold text-secondary">Business Legal Name</label>
                        <input type="text" class="form-control py-2" id="name" name="name" value="{{ $company->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label small fw-semibold text-secondary">Registered Corporate Email</label>
                        <input type="email" class="form-control py-2" id="email" name="email" value="{{ $company->email }}">
                    </div>
                    <div class="mb-4">
                        <label for="phone" class="form-label small fw-semibold text-secondary">Primary Mobile/Contact Line</label>
                        <input type="text" class="form-control py-2" id="phone" name="phone" value="{{ $company->phone }}">
                    </div>

                    <div class="row g-2">
                        <div class="col-6">
                            <button type="submit" class="btn btn-primary w-100 py-2 fw-medium">Commit Changes</button>
                        </div>
                        <div class="col-6">
                            <a href="/companies" class="btn btn-outline-secondary w-100 py-2">Abort</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection