@extends('layouts.app')

@section('title', 'Detail Siswa')
@section('page-title', 'Detail Siswa - ' . $student->name)

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-person"></i> Informasi Siswa</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="fw-bold">NISN</td>
                        <td>: {{ $student->nisn }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Nama</td>
                        <td>: {{ $student->name }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Kelas</td>
                        <td>: {{ $student->class->name }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Jenis Kelamin</td>
                        <td>: {{ $student->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Tanggal Lahir</td>
                        <td>: {{ $student->birth_date?->format('d M Y') ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">No. HP</td>
                        <td>: {{ $student->phone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Alamat</td>
                        <td>: {{ $student->address ?? '-' }}</td>
                    </tr>
                </table>
                <div class="d-flex gap-2">
                    <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <form action="{{ route('students.destroy', $student) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-credit-card"></i> Status Pembayaran</h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded">
                            <small class="text-muted">Total Tunggakan</small>
                            <h4 class="text-danger mb-0">Rp {{ number_format($paymentSummary['total_due'], 0, ',', '.') }}</h4>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded">
                            <small class="text-muted">Total Sudah Dibayar</small>
                            <h4 class="text-success mb-0">Rp {{ number_format($paymentSummary['total_paid'], 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
                <h6 class="mt-4 mb-3">Riwayat Pembayaran</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>SPP</th>
                                <th>Nominal</th>
                                <th>Status</th>
                                <th>Tanggal Bayar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($student->payments as $payment)
                                <tr>
                                    <td>{{ $payment->spp->name }}</td>
                                    <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                    <td>
                                        @if ($payment->status === 'paid')
                                            <span class="badge bg-success">Lunas</span>
                                        @else
                                            <span class="badge bg-warning">Tertunda</span>
                                        @endif
                                    </td>
                                    <td>{{ $payment->payment_date?->format('d M Y') ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Belum ada pembayaran</td>
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
