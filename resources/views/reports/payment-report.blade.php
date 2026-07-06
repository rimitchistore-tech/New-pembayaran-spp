@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0"><i class="fas fa-list"></i> Laporan Pembayaran</h1>
                    <small class="text-muted">Daftar semua pembayaran SPP</small>
                </div>
                <div>
                    <form action="{{ route('reports.payment.export') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-download"></i> Export Excel
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('reports.payment') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Cari Siswa</label>
                            <input type="text" name="student_search" class="form-control" 
                                   placeholder="Nama atau ID Siswa" value="{{ request('student_search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">-- Semua Status --</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Kelas</label>
                            <select name="class" class="form-select">
                                <option value="">-- Semua Kelas --</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class }}" {{ request('class') == $class ? 'selected' : '' }}>
                                        {{ $class }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Dari Tanggal</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Sampai Tanggal</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i> Filter
                            </button>
                        </div>
                        <div class="col-md-12">
                            <a href="{{ route('reports.payment') }}" class="btn btn-sm btn-secondary">
                                <i class="fas fa-redo"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Pembayaran</h5>
                    <small class="text-muted">Total: {{ $payments->total() }} pembayaran</small>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>No. Referensi</th>
                                <th>Nama Siswa</th>
                                <th>ID Siswa</th>
                                <th>Kelas</th>
                                <th>Metode</th>
                                <th>Jumlah</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Verifikasi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $index => $payment)
                            <tr>
                                <td>{{ ($payments->currentPage() - 1) * $payments->perPage() + $loop->iteration }}</td>
                                <td><strong>{{ $payment->reference_number }}</strong></td>
                                <td>{{ $payment->user->name }}</td>
                                <td>{{ $payment->user->student_id ?? '-' }}</td>
                                <td>{{ $payment->user->class ?? '-' }}</td>
                                <td><small>{{ $payment->paymentMethod->name ?? '-' }}</small></td>
                                <td class="text-end fw-bold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                <td><small>{{ $payment->payment_date->format('d-m-Y H:i') }}</small></td>
                                <td>
                                    <span class="badge badge-{{ $payment->status === 'paid' ? 'success' : ($payment->status === 'pending' ? 'warning' : ($payment->status === 'verified' ? 'info' : 'danger')) }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($payment->verified_by)
                                        <small>{{ $payment->verifiedBy->name }}</small><br>
                                        <small class="text-muted">{{ $payment->verified_date->format('d-m-Y') }}</small>
                                    @else
                                        <small class="text-muted">-</small>
                                    @endif
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
                                        <a href="{{ route('reports.invoice.download', $payment->id) }}" 
                                           class="btn btn-outline-success" title="Download Invoice">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox"></i> Tidak ada data pembayaran
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $payments->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .badge-success {
        background-color: #28A745;
    }
    .badge-warning {
        background-color: #FFC107;
        color: #333;
    }
    .badge-info {
        background-color: #17A2B8;
    }
    .badge-danger {
        background-color: #DC3545;
    }
</style>
@endpush

@endsection
