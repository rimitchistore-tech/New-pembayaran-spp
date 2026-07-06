@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg mt-5">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="card-title">SPP Payment System</h2>
                        <p class="text-muted">Masuk ke akun Anda</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                id="password" name="password" required>
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Ingat saya</label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">Masuk</button>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="mb-2">Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a></p>
                        <p><a href="{{ route('password.request') }}">Lupa password?</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
