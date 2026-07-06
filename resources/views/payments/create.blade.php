@extends('layouts.app')

@section('title', 'Buat Pembayaran')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h1 class="h3 mb-4">Buat Pembayaran Baru</h1>

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <h6 class="mb-2">Ada kesalahan:</h6>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('payments.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="amount" class="form-label">Jumlah Pembayaran <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control @error('amount') is-invalid @enderror" 
                                    id="amount" name="amount" min="1000" step="1000"
                                    value="{{ old('amount') }}" required>
                            </div>
                            @error('amount')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                            <small class="text-muted">Minimum Rp 1.000</small>
                        </div>

                        <div class="mb-3">
                            <label for="payment_method_id" class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                            <select class="form-select @error('payment_method_id') is-invalid @enderror" 
                                id="payment_method_id" name="payment_method_id" required>
                                <option value="">-- Pilih Metode --</option>
                                @foreach($paymentMethods as $method)
                                    <option value="{{ $method->id }}" @selected(old('payment_method_id') == $method->id)>
                                        {{ $method->name }}
                                        @if($method->description)
                                            - {{ $method->description }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('payment_method_id')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Keterangan</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                id="description" name="description" rows="3" 
                                placeholder="Masukkan keterangan pembayaran (opsional)">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="proof_file" class="form-label">Bukti Pembayaran</label>
                            <input type="file" class="form-control @error('proof_file') is-invalid @enderror" 
                                id="proof_file" name="proof_file" accept=".pdf,.jpg,.jpeg,.png">
                            @error('proof_file')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                            <small class="text-muted">Format: PDF, JPG, PNG (Max 5MB)</small>
                        </div>

                        <div class="d-grid gap-2 d-sm-flex justify-content-sm-end">
                            <a href="{{ route('payments.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Kirim Pembayaran</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
