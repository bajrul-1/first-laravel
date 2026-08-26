<div class="card border-0 shadow-sm rounded-3 bg-white p-4">
    <form wire:submit.prevent="saveEmployee">

        <!-- 1. PERSONAL DETAILS -->
        <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
            <i class="fa-solid fa-user text-primary me-2"></i>Personal Details
        </h5>

        <div class="row g-3 mb-4">
            <!-- Full Name -->
            <div class="col-12 col-md-6">
                <label class="form-label fw-semibold small">Full Name <span class="text-danger">*</span></label>
                <input type="text" wire:model.defer="name" class="form-control @error('name') is-invalid @enderror"
                    placeholder="e.g. Rahul Sharma">
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Email Address -->
            <div class="col-12 col-md-6">
                <label class="form-label fw-semibold small">Email Address <span class="text-danger">*</span></label>
                <input type="email" wire:model.defer="email" class="form-control @error('email') is-invalid @enderror"
                    placeholder="rahul@example.com">
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Phone Number -->
            <div class="col-12 col-md-6">
                <label class="form-label fw-semibold small">Phone Number <span class="text-danger">*</span></label>
                <input type="text" wire:model.defer="phone" class="form-control @error('phone') is-invalid @enderror"
                    placeholder="+91 9876543210">
                @error('phone')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Profile Photo -->
            <div class="col-12 col-md-6">
                <label class="form-label fw-semibold small">Profile Photo</label>
                <input type="file" wire:model="profile_photo"
                    class="form-control @error('profile_photo') is-invalid @enderror" accept="image/*">
                <div wire:loading wire:target="profile_photo" class="text-primary small mt-1">
                    <i class="fa-solid fa-spinner fa-spin me-1"></i> Uploading Photo...
                </div>
                @if ($profile_photo)
                    <div class="mt-2">
                        <img src="{{ $profile_photo->temporaryUrl() }}" class="rounded-circle img-thumbnail"
                            style="height: 60px; width: 60px; object-fit: cover;">
                    </div>
                @endif
                @error('profile_photo')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- 2. JOB & SALARY DETAILS -->
        <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
            <i class="fa-solid fa-briefcase text-success me-2"></i>Job & Salary Information
        </h5>

        <div class="row g-3 mb-4">
            <!-- Designation / Role -->
            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold small">Designation / Role <span
                        class="text-danger">*</span></label>
                <select wire:model.defer="designation" class="form-select @error('designation') is-invalid @enderror">
                    <option value="salesman">🚚 Salesman</option>
                    <option value="worker">👨‍🍳 Bakery Worker / Baker</option>
                    <option value="manager">👔 Store Manager</option>
                </select>
                @error('designation')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Monthly Salary -->
            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold small">Monthly Salary (₹) <span
                        class="text-danger">*</span></label>
                <input type="number" step="0.01" wire:model.defer="salary"
                    class="form-control @error('salary') is-invalid @enderror" placeholder="15000.00">
                @error('salary')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Joining Date -->
            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold small">Joining Date <span class="text-danger">*</span></label>
                <input type="date" wire:model.defer="joining_date"
                    class="form-control @error('joining_date') is-invalid @enderror">
                @error('joining_date')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- 3. ACCOUNT SECURITY -->
        <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
            <i class="fa-solid fa-lock text-warning me-2"></i>Login Credentials
        </h5>

        <div class="row g-3 mb-4">
            <!-- Login Password -->
            <div class="col-12 col-md-6">
                <label class="form-label fw-semibold small">Login Password <span class="text-danger">*</span></label>
                <input type="password" wire:model.defer="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="Set password for staff login">
                @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- SUBMIT BUTTON -->
        <div class="d-flex justify-content-end gap-2 border-top pt-3">
            <button type="submit" wire:loading.attr="disabled" class="btn btn-primary fw-bold px-4">
                <span wire:loading.remove><i class="fa-solid fa-user-plus me-1"></i> Onboard Employee</span>
                <span wire:loading><i class="fa-solid fa-spinner fa-spin me-1"></i> Saving Employee...</span>
            </button>
        </div>
    </form>
</div>
