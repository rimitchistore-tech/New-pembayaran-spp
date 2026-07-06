@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0"><i class="fas fa-users"></i> Laporan Kelas: {{ $class }}</h1>
                    <small class="text-muted">Pembayaran siswa untuk kelas ini</small>
                </div>
                <div>
                    <form action="{{ route('reports.class.export', $class) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-download"></i> Export Excel
                        </button>
                    </form>
                    <a href="{{ route('reports.payment') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary">
                <div class="card-body">
                    <div class="text-primary font-weight-bold text-uppercase mb-1">Total Siswa</div>
                    <div class="h3 mb-0">{{ $stats['total_students'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-info">
                <div class="card-body">
                    <div class="text-info font-weight-bold text-uppercase mb-1">Total Pembayaran</div>
                    <div class="h3 mb-0">{{ $stats['total_payments'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-success">
                <div class="card-body">
                    <div class="text-success font-weight-bold text-uppercase mb-1">Sudah Dibayar</div>
                    <div class="h3 mb-0">{{ $stats['paid_count'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning">
                <div class="card-body">
                    <div class="text-warning font-weight-bold text-uppercase mb-1">Total Jumlah</div>
                    <div class="h3 mb-0">Rp {{ number_format($stats['total_amount'], 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment List -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Daftar Pembayaran Kelas {{ $class }}</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>No. Referensi</th>
                                <th>Nama Siswa</th>
                                <th>ID Siswa</th>
                                <th>Metode</th>
                                <th>Jumlah</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $index => $payment)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ $payment->reference_number }}</strong></td>
                                <td>{{ $payment->user->name }}</td>
                                <td>{{ $payment->user->student_id ?? '-' }}</td>
                                <td><small>{{ $payment->paymentMethod->name ?? '-' }}</small></td>
                                <td class="text-end fw-bold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                <td><small>{{ $payment->payment_date->format('d-m-Y H:i') }}</small></td>
                                <td>
                                    <span class="badge badge-{{ $payment->status === 'paid' ? 'success' : ($payment->status === 'pending' ? 'warning' : ($payment->status === 'verified' ? 'info' : 'danger')) }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('reports.invoice.preview', $payment->id) }}" 
                                           class="btn btn-outline-primary" title="Lihat Invoice" target="_blank">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                        <a href="{{ route('reports.slip.print', $payment->id) }}" 
                                           class="btn btn-outline-info" title="Print Slip" target="_blank">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox"></i> Tidak ada data pembayaran untuk kelas ini
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
