@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3 mb-0">
                <i class="fas fa-chart-pie"></i> Dashboard Laporan
            </h1>
            <small class="text-muted">Ringkasan data pembayaran SPP</small>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary">
                <div class="card-body">
                    <div class="text-primary font-weight-bold text-uppercase mb-1">Total Pembayaran</div>
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
                    <div class="text-warning font-weight-bold text-uppercase mb-1">Menunggu Verifikasi</div>
                    <div class="h3 mb-0">{{ $stats['pending_count'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-danger">
                <div class="card-body">
                    <div class="text-danger font-weight-bold text-uppercase mb-1">Ditolak</div>
                    <div class="h3 mb-0">{{ $stats['rejected_count'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Jumlah</h5>
                    <h3 class="text-primary">Rp {{ number_format($stats['total_amount'], 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Chart -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Statistik Bulanan (6 Bulan Terakhir)</h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Payments -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Pembayaran Terbaru</h5>
                    <a href="{{ route('reports.payment') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-eye"></i> Lihat Semua
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No. Referensi</th>
                                <th>Nama Siswa</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_payments as $payment)
                            <tr>
                                <td><strong>{{ $payment->reference_number }}</strong></td>
                                <td>{{ $payment->user->name }}</td>
                                <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge badge-{{ $payment->status === 'paid' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                                <td>{{ $payment->payment_date->format('d-m-Y H:i') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('reports.invoice.preview', $payment->id) }}" class="btn btn-outline-primary" title="Lihat Invoice">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                        <a href="{{ route('reports.slip.print', $payment->id) }}" class="btn btn-outline-info" title="Print Slip">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">Tidak ada data pembayaran</td>
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
    const ctx = document.getElementById('monthlyChart').getContext('2d');
    const monthlyChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($monthly_data->pluck('month')) !!},
            datasets: [{
                label: 'Total Pembayaran (Rp)',
                data: {!! json_encode($monthly_data->pluck('total')) !!},
                backgroundColor: '#007bff',
                borderColor: '#0056b3',
                borderWidth: 1
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
