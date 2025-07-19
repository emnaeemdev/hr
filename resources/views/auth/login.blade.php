<x-guest-layout>
    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success mb-3" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="text-center mb-4">
            <h4 class="text-primary">تسجيل الدخول</h4>
            <p class="text-muted">أدخل بياناتك للوصول إلى النظام</p>
        </div>

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">
                <i class="fas fa-envelope me-2"></i>البريد الإلكتروني
            </label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                   name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
                   placeholder="أدخل البريد الإلكتروني">
            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">
                <i class="fas fa-lock me-2"></i>كلمة المرور
            </label>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                   name="password" required autocomplete="current-password" 
                   placeholder="أدخل كلمة المرور">
            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
            <label class="form-check-label" for="remember_me">
                تذكرني
            </label>
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-sign-in-alt me-2"></i>تسجيل الدخول
            </button>
        </div>

        @if (Route::has('password.request'))
            <div class="text-center mt-3">
                <a href="{{ route('password.request') }}" class="text-decoration-none">
                    <i class="fas fa-question-circle me-1"></i>نسيت كلمة المرور؟
                </a>
            </div>
        @endif

        <hr class="my-4">
        <div class="text-center">
            <small class="text-muted">
                <strong>بيانات تجريبية:</strong><br>
                المدير: admin@company.com<br>
                الموارد البشرية: hr@company.com<br>
                كلمة المرور: password123
            </small>
        </div>
    </form>
</x-guest-layout>
