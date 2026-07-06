@extends('layouts.app')

@section('title', 'Detail Pembayaran')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $payment->reference_number }}</h5>
                    @if($payment->isPending())
                        <span class="badge bg-warning">Pending</span>
                    @elseif($payment->isVerified())
                        <span class="badge bg-info">Terverifikasi</span>
                    @elseif($payment->isPaid())
                        <span class="badge bg-success">Lunas</span>
                    @else
                        <span class="badge bg-danger">Ditolak</span>
                    @endif
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Nama Siswa</h6>
                            <p class="fw-bold">{{ $payment->user->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Email</h6>
                            <p>{{ $payment->user->email }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Metode Pembayaran</h6>
                            <p class="fw-bold">{{ $payment->paymentMethod?->name ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Jumlah</h6>
                            <p class="fw-bold" style="font-size: 1.25rem; color: #28a745;">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Tanggal Pembayaran</h6>
                            <p>{{ $payment->payment_date?->format('d M Y H:i') ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Keterangan</h6>
                            <p>{{ $payment->description ?? '-' }}</p>
                        </div>
                    </div>

                    @if($payment->isRejected())
                        <div class="alert alert-danger">
                            <h6 class="mb-2">Alasan Penolakan:</h6>
                            <p class="mb-0">{{ $payment->rejection_reason }}</p>
                        </div>
                    @endif

                    @if($payment->proof_file)
                        <div class="mb-3">
                            <h6 class="text-muted">Bukti Pembayaran</h6>
                            <a href="{{ asset('storage/' . $payment->proof_file) }}" class="btn btn-sm btn-info" target="_blank">
                                <i class="fas fa-download"></i> Download Bukti
                            </a>
                        </div>
                    @endif

                    @if($payment->isVerified())
                        <div class="mb-3">
                            <h6 class="text-muted">Diverifikasi oleh</h6>
                            <p>
                                {{ $payment->verifiedBy?->name ?? '-' }}
                                <br>
                                <small class="text-muted">{{ $payment->verified_date?->format('d M Y H:i') }}</small>
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            @if($payment->verifications->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Riwayat Verifikasi</h5>
                    </div>
                    <div class="card-body">
                        @foreach($payment->verifications as $verification)
                            <div class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <p class="fw-bold">{{ $verification->verifier->name }}</p>
                                        <p class="text-muted mb-0">{{ $verification->created_at->format('d M Y H:i') }}</p>
                                    </div>
                                    <span class="badge bg-{{ $verification->action === 'verified' ? 'success' : ($verification->action === 'rejected' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($verification->action) }}
                                    </span>
                                </div>
                                @if($verification->notes)
                                    <p class="text-muted mb-0 mt-2">{{ $verification->notes }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($payment->isPending() && auth()->user()->isStudent() && auth()->id() === $payment->user_id)
                <div class="mt-3 d-grid gap-2 d-sm-flex justify-content-sm-end">
                    <a href="{{ route('payments.edit', $payment) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('payments.destroy', $payment) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger" onclick="return confirm('Hapus pembayaran ini?')">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
