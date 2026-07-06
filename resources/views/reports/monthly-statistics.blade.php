@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3 mb-0">
                <i class="fas fa-calendar-alt"></i> Statistik Bulanan
            </h1>
            <small class="text-muted">Laporan pembayaran bulan {{ $month_label }}</small>
        </div>
    </div>

    <!-- Month Selector -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('reports.monthly') }}" class="row align-items-end g-3">
                        <div class="col-md-3">
                            <label class="form-label">Pilih Bulan</label>
                            <input type="month" name="month" class="form-control" value="{{ $month }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Tampilkan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary">
                <div class="card-body">
                    <div class="text-primary font-weight-bold text-uppercase mb-1">Total Pembayaran</div>
                    <div class="h3 mb-0">{{ $status_breakdown->sum('count') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-success">
                <div class="card-body">
                    <div class="text-success font-weight-bold text-uppercase mb-1">Total Jumlah</div>
                    <div class="h3 mb-0">Rp {{ number_format($status_breakdown->sum('total'), 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-info">
                <div class="card-body">
                    <div class="text-info font-weight-bold text-uppercase mb-1">Rata-rata per Hari</div>
                    <div class="h3 mb-0">Rp {{ number_format($status_breakdown->sum('total') / count($daily_totals), 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning">
                <div class="card-body">
                    <div class="text-warning font-weight-bold text-uppercase mb-1">Metode Pembayaran</div>
                    <div class="h3 mb-0">{{ $method_breakdown->count() }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Chart -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Grafik Pembayaran Harian</h5>
                </div>
                <div class="card-body">
                    <canvas id="dailyChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Breakdown -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Breakdown Status</h5>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Status</th>
                                <th>Jumlah</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($status_breakdown as $status)
                            <tr>
                                <td>
                                    <span class="badge badge-{{ $status->status === 'paid' ? 'success' : ($status->status === 'pending' ? 'warning' : ($status->status === 'verified' ? 'info' : 'danger')) }}">
                                        {{ ucfirst($status->status) }}
                                    </span>
                                </td>
                                <td>{{ $status->count }}</td>
                                <td class="fw-bold">Rp {{ number_format($status->total, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Method Breakdown -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Breakdown Metode Pembayaran</h5>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Metode</th>
                                <th>Jumlah</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($method_breakdown as $method)
                            <tr>
                                <td>{{ $method->paymentMethod->name ?? 'Unknown' }}</td>
                                <td>{{ $method->count }}</td>
                                <td class="fw-bold">Rp {{ number_format($method->total, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Students -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Top 10 Siswa dengan Pembayaran Terbanyak</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Jumlah Pembayaran</th>
                                <th>Total Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($top_students as $index => $student)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $student->user->name }}</td>
                                <td>{{ $student->user->class ?? '-' }}</td>
                                <td><span class="badge badge-info">{{ $student->count }}</span></td>
                                <td class="fw-bold">Rp {{ number_format($student->total, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">Tidak ada data</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const dailyData = {!! json_encode($daily_totals) !!};
    const days = dailyData.map(d => 'Hari ' + d.day);
    const totals = dailyData.map(d => d.total);

    const ctx = document.getElementById('dailyChart').getContext('2d');
    const dailyChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: days,
            datasets: [{
                label: 'Total Pembayaran Harian (Rp)',
                data: totals,
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush

@endsection
