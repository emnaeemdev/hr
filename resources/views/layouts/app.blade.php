<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'نظام إدارة الموظفين')</title>
    
    <!-- Bootstrap RTL CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts - Cairo -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
                .card:hover {
            transform: none !important;
            box-shadow: none !important;
        }
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f8f9fa;
        }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            margin: 5px 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: rgba(255,255,255,0.2);
            color: white;
            transform: translateX(-5px);
        }
        .main-content {
            background-color: #ffffff;
            min-height: 100vh;
            border-radius: 15px 0 0 0;
            box-shadow: -2px 0 10px rgba(0,0,0,0.05);
        }
        .navbar {
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            border-radius: 0 0 15px 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 25px;
            padding: 10px 25px;
        }
        .alert {
            border-radius: 10px;
            border: none;
        }
    </style>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0">
                <div class="sidebar">
                    <div class="text-center py-4">
                        <h4 class="text-white mb-0">
                            <i class="fas fa-users me-2"></i>
                            نظام الموظفين
                        </h4>
                        <small class="text-white-50">مرحباً {{ Auth::user()->name }}</small>
                    </div>
                    
                    <nav class="nav flex-column">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i> لوحة التحكم
                        </a>
                        <a class="nav-link {{ request()->routeIs('branches.*') ? 'active' : '' }}" href="{{ route('branches.index') }}">
                            <i class="fas fa-building me-2"></i> الفروع
                        </a>
                        <a class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}" href="{{ route('employees.index') }}">
                            <i class="fas fa-users me-2"></i> الموظفين
                        </a>
                        <a class="nav-link {{ request()->routeIs('tools.*') ? 'active' : '' }}" href="{{ route('tools.index') }}">
                            <i class="fas fa-tools me-2"></i> الأدوات
                        </a>
                        <a class="nav-link {{ request()->routeIs('employee-advances.*') ? 'active' : '' }}" href="{{ route('employee-advances.index') }}">
                            <i class="fas fa-money-bill-wave me-2"></i> السلف
                        </a>
                        <a class="nav-link {{ request()->routeIs('employee-documents.*') ? 'active' : '' }}" href="{{ route('employee-documents.index') }}">
                            <i class="fas fa-file-alt me-2"></i> المستندات
                        </a>
                        <a class="nav-link {{ request()->routeIs('custom-fields.*') ? 'active' : '' }}" href="{{ route('custom-fields.index') }}">
                            <i class="fas fa-cogs me-2"></i> الحقول المخصصة
                        </a>
                        
                        <hr class="text-white-50 mx-3">
                        
                        <a class="nav-link" href="{{ route('profile.edit') }}">
                            <i class="fas fa-user-cog me-2"></i> الملف الشخصي
                        </a>
                        
                        <form method="POST" action="{{ route('logout') }}" class="mx-3 mt-2">
                            @csrf
                            <button type="submit" class="btn btn-outline-light btn-sm w-100">
                                <i class="fas fa-sign-out-alt me-2"></i> تسجيل الخروج
                            </button>
                        </form>
                    </nav>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 px-0">
                <div class="main-content">
                    <!-- Top Navigation -->
                    <nav class="navbar navbar-expand-lg">
                        <div class="container-fluid">
                            <h5 class="navbar-brand text-white mb-0">
                                @yield('page-title', 'لوحة التحكم')
                            </h5>
                            <div class="navbar-nav ms-auto">
                                <span class="navbar-text text-white">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ now()->format('Y/m/d') }}
                                </span>
                            </div>
                        </div>
                    </nav>
                    
                    <!-- Content Area -->
                    <div class="container-fluid p-4">
                        <!-- Success Messages -->
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        
                        <!-- Error Messages -->
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        
                        <!-- Validation Errors -->
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>يرجى تصحيح الأخطاء التالية:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>
    
<!-- jQuery - يجب تحميله أولاً -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- Bootstrap Bundle JS (مع Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Scripts from child views -->
@stack('scripts')
</body>
</html>
