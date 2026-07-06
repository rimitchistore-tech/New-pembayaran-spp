@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg mt-5">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="card-title">Verifikasi Email</h2>
                        <p class="text-muted">Kami telah mengirim link verifikasi ke email Anda</p>
                    </div>

                    @if (session('status') == 'verification-link-sent')
                        <div class="alert alert-success alert-dismissible fade show">
                            Link verifikasi baru telah dikirim ke email Anda.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="mb-4">
                        <p class="text-center">Silakan klik link yang ada di email untuk memverifikasi alamat email Anda.</p>
                        <p class="text-center">Jika Anda tidak menerima email, kami dapat mengirimkan link verifikasi yang baru.</p>
                    </div>

                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary w-100 mb-3">Kirim Ulang Email Verifikasi</button>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
