@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg mt-5">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="card-title">Reset Password</h2>
                        <p class="text-muted">Masukkan password baru Anda</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                value="{{ $email ?? old('email') }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password Baru</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                id="password" name="password" required>
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="text-muted">Minimal 8 karakter, harus ada huruf dan angka</small>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" id="password_confirmation" 
                                name="password_confirmation" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">Reset Password</button>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <p><a href="{{ route('login') }}">Kembali ke login</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
