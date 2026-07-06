@extends('layouts.app')

@section('title', 'Data Siswa')
@section('page-title', 'Data Siswa')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bi bi-table"></i> Daftar Siswa</h6>
        <a href="{{ route('students.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Siswa
        </a>
    </div>
    <div class="card-body pb-0">
        <form method="GET" class="mb-3">
            <div class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama atau NISN..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <select name="class_id" class="form-select">
                        <option value="">-- Semua Kelas --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>NISN</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Jenis Kelamin</th>
                    <th>Status Pembayaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    <tr>
                        <td><strong>{{ $student->nisn }}</strong></td>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->class->name }}</td>
                        <td>
                            @if ($student->gender === 'male')
                                <span class="badge bg-info">Laki-laki</span>
                            @else
                                <span class="badge bg-pink" style="background-color: #e83e8c;">Perempuan</span>
                            @endif
                        </td>
                        <td>
                            @if ($student->totalDue() == 0)
                                <span class="badge bg-success">Lunas</span>
                            @else
                                <span class="badge bg-warning">Rp {{ number_format($student->totalDue(), 0, ',', '.') }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-info" title="Lihat">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-warning" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('students.destroy', $student) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Tidak ada data siswa
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<nav class="mt-4">
    {{ $students->links('pagination::bootstrap-5') }}
</nav>
@endsection
