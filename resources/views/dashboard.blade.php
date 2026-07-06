@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Siswa</h6>
                        <h3 class="mb-0">{{ $totalStudents }}</h3>
                    </div>
                    <i class="bi bi-people" style="font-size: 2rem; color: var(--primary);"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Pendapatan Terkumpul</h6>
                        <h3 class="mb-0">Rp {{ number_format($totalCollected, 0, ',', '.') }}</h3>
                    </div>
                    <i class="bi bi-cash-coin" style="font-size: 2rem; color: var(--success);"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Pembayaran Tertunda</h6>
                        <h3 class="mb-0">{{ $pendingPayments }}</h3>
                    </div>
                    <i class="bi bi-hourglass-split" style="font-size: 2rem; color: var(--warning);"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card danger">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Siswa Belum Lunas</h6>
                        <h3 class="mb-0">{{ $studentsWithDebt }}</h3>
                    </div>
                    <i class="bi bi-exclamation-triangle" style="font-size: 2rem; color: var(--danger);"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-list-check"></i> Pembayaran Terakhir</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Siswa</th>
                                <th>SPP</th>
                                <th>Nominal</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPayments as $payment)
                                <tr>
                                    <td>
                                        <strong>{{ $payment->student->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $payment->student->nisn }}</small>
                                    </td>
                                    <td>{{ $payment->spp->name }}</td>
                                    <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                    <td>
                                        @if ($payment->status === 'paid')
                                            <span class="badge bg-success">Lunas</span>
                                        @elseif ($payment->status === 'pending')
                                            <span class="badge bg-warning">Tertunda</span>
                                        @else
                                            <span class="badge bg-danger">Terlambat</span>
                                        @endif
                                    </td>
                                    <td>{{ $payment->payment_date?->format('d M Y') ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        Tidak ada data pembayaran
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-bar-chart"></i> Status Kelas</h6>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @foreach($classStats as $class)
                        <div class="list-group-item px-0 py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $class->name }}</h6>
                                    <small class="text-muted">{{ $class->students_count }} siswa</small>
                                </div>
                                <span class="badge bg-info">{{ $class->students_with_debt }} Belum Lunas</span>
                            </div>
                            <div class="progress mt-2" style="height: 6px;">
                                <div class="progress-bar bg-success" style="width: {{ ($class->students_count - $class->students_with_debt) / $class->students_count * 100 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
