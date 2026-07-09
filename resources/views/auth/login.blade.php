<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Access Gateway - BakeryERP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container">
        <div class="row min-vh-100 align-items-center justify-content-center">
            <div class="col-12 col-md-6 col-lg-5 col-xl-4">
                
                <div class="text-center mb-4">
                    <h2 class="fw-bold text-primary mb-1">🍞 BakeryERP</h2>
                    <p class="text-muted small">Central Identity & Access Management Gateway</p>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-3 small">
                        ⚠️ {{ session('error') }}
                    </div>
                @endif

                <div class="card shadow border-0 rounded-3 bg-white">
                    <div class="card-body p-4">
                        <h5 class="fw-bold text-dark mb-1">Account Authentication</h5>
                        <p class="text-muted small mb-4">Provide registered terminal credentials to establish route session.</p>

                        <form action="{{ route('login.verify') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label small fw-medium text-secondary">Corporate Email Address</label>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       class="form-control rounded-2 @error('email') is-invalid @enderror" 
                                       value="{{ old('email') }}" 
                                       placeholder="name@company.com" 
                                       required 
                                       autofocus>
                                @error('email')
                                    <div class="invalid-feedback small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label small fw-medium text-secondary">Security Password</label>
                                <input type="password" 
                                       name="password" 
                                       id="password" 
                                       class="form-control rounded-2 @error('password') is-invalid @enderror" 
                                       placeholder="••••••••" 
                                       required>
                                @error('password')
                                    <div class="invalid-feedback small">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2 fw-medium rounded-2 shadow-sm">
                                Verify & Sign In
                            </button>
                        </form>

                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="/" class="text-decoration-none small text-secondary">&larr; Return to Public Domain</a>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bundle.min.js"></script>
</body>
</html>