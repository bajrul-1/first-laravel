<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Login - Bakery ERP</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-body-tertiary d-flex align-items-center justify-content-center vh-100">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-5 col-lg-4">
                
                <!-- Bakery Branding Logo/Text -->
                <div class="text-center mb-4">
                    <h2 class="fw-bold text-primary m-0"><i class="fa-solid fa-bread-slice me-2"></i>Bakery ERP</h2>
                    <small class="text-muted text-uppercase fw-semibold tracking-wider" style="font-size: 0.75rem;">Enterprise Partner Portal</small>
                </div>

                <!-- Login Card -->
                <div class="card bg-body border shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <h5 class="fw-bold text-body mb-3 text-center">Owner Sign In</h5>
                        
                        <form action="/owner/login" method="POST">
                            @csrf

                            <!-- Field 1: Email Address -->
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold small">Registered Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0"><i class="fa-solid fa-envelope"></i></span>
                                    <input type="email" 
                                           class="form-control border-start-0 @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           placeholder="owner@bakery.com" 
                                           required 
                                           autofocus>
                                    @error('email')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Field 2: Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold small">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0"><i class="fa-solid fa-lock"></i></span>
                                    <input type="password" 
                                           class="form-control border-start-0 @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           placeholder="••••••••" 
                                           required>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Remember Me Checkbox -->
                            <div class="mb-4 d-flex justify-content-between align-items-center">
                                <div class="form-check m-0">
                                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                    <label class="form-check-label small text-muted" for="remember">Remember this device</label>
                                </div>
                            </div>

                            <!-- Submit Action Button -->
                            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold shadow-sm rounded-2">
                                <i class="fa-solid fa-right-to-bracket me-2"></i>Access Dashboard
                            </button>
                        </form>

                    </div>
                </div>

                <!-- Bottom Copyright Node -->
                <div class="text-center mt-4">
                    <small class="text-muted">&copy; {{ date('Y') }} Bakery ERP System. All nodes secured.</small>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>