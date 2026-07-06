<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Slip {{ $slip_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Courier New', monospace;
            color: #333;
            background-color: #fff;
        }
        .slip-container {
            width: 210mm;
            height: 148mm;
            padding: 10mm;
            background-color: white;
            color: #000;
            page-break-after: always;
            position: relative;
        }
        .slip-header {
            text-align: center;
            border-bottom: 2px dashed #000;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }
        .school-logo {
            max-width: 40px;
            height: auto;
            margin-bottom: 5px;
        }
        .slip-header h1 {
            font-size: 14px;
            font-weight: bold;
            margin: 5px 0;
        }
        .slip-header p {
            font-size: 10px;
            margin: 2px 0;
        }
        .slip-title {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            margin: 8px 0;
            text-decoration: underline;
        }
        .slip-content {
            font-size: 11px;
            line-height: 1.8;
        }
        .slip-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
        }
        .slip-label {
            font-weight: bold;
            flex: 0 0 40%;
        }
        .slip-colon {
            flex: 0 0 5%;
            text-align: center;
        }
        .slip-value {
            flex: 1;
            text-align: left;
        }
        .slip-amount-box {
            border: 2px solid #000;
            padding: 8px;
            margin: 10px 0;
            text-align: center;
            background-color: #f5f5f5;
        }
        .slip-amount-label {
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 3px;
        }
        .slip-amount-value {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 3px;
        }
        .slip-amount-text {
            font-size: 9px;
            font-style: italic;
            word-break: break-word;
        }
        .status-label {
            display: inline-block;
            padding: 2px 6px;
            font-weight: bold;
            font-size: 10px;
            text-align: center;
            min-width: 60px;
            border: 1px solid #000;
        }
        .slip-footer {
            margin-top: 15px;
            border-top: 2px dashed #000;
            padding-top: 8px;
            font-size: 9px;
            text-align: center;
        }
        .slip-signature {
            display: flex;
            justify-content: space-around;
            margin-top: 12px;
            font-size: 9px;
        }
        .sig-block {
            text-align: center;
            width: 30%;
        }
        .sig-line {
            border-top: 1px solid #000;
            margin: 20px 0 3px 0;
            min-height: 20px;
        }
        .sig-name {
            font-weight: bold;
            font-size: 8px;
        }
        .slip-divider {
            border-bottom: 2px dashed #000;
            margin: 15px 0;
            padding-bottom: 8px;
        }
        .note {
            font-size: 8px;
            text-align: center;
            color: #666;
            margin-top: 5px;
        }
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .slip-container {
                margin: 0;
                padding: 10mm;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <!-- CUSTOMER COPY -->
    <div class="slip-container">
        <div class="slip-header">
            @if(file_exists($school_logo))
                <img src="{{ $school_logo }}" alt="Logo" class="school-logo">
            @endif
            <h1>{{ $school_name }}</h1>
            <p>BUKTI PEMBAYARAN SPP</p>
        </div>

        <div class="slip-title">LEMBAR SISWA</div>

        <div class="slip-content">
            <div class="slip-row">
                <div class="slip-label">No. Slip</div>
                <div class="slip-colon">:</div>
                <div class="slip-value">{{ $slip_number }}</div>
            </div>
            <div class="slip-row">
                <div class="slip-label">Tanggal</div>
                <div class="slip-colon">:</div>
                <div class="slip-value">{{ $slip_date }}</div>
            </div>
            <div class="slip-row">
                <div class="slip-label">Nama Siswa</div>
                <div class="slip-colon">:</div>
                <div class="slip-value">{{ $student_name }}</div>
            </div>
            <div class="slip-row">
                <div class="slip-label">ID Siswa</div>
                <div class="slip-colon">:</div>
                <div class="slip-value">{{ $student_id }}</div>
            </div>
            <div class="slip-row">
                <div class="slip-label">Kelas</div>
                <div class="slip-colon">:</div>
                <div class="slip-value">{{ $class }}</div>
            </div>
            <div class="slip-row">
                <div class="slip-label">Metode Pembayaran</div>
                <div class="slip-colon">:</div>
                <div class="slip-value">{{ $payment_method }}</div>
            </div>
        </div>

        <div class="slip-amount-box">
            <div class="slip-amount-label">JUMLAH PEMBAYARAN</div>
            <div class="slip-amount-value">Rp {{ $amount }}</div>
            <div class="slip-amount-text">{{ $amount_text }}</div>
        </div>

        <div class="slip-content">
            <div class="slip-row">
                <div class="slip-label">Status</div>
                <div class="slip-colon">:</div>
                <div class="slip-value">
                    <span class="status-label" style="background-color: {{ $status_color }}; color: white;">
                        {{ $status }}
                    </span>
                </div>
            </div>
            <div class="slip-row">
                <div class="slip-label">Diverifikasi Oleh</div>
                <div class="slip-colon">:</div>
                <div class="slip-value">{{ $verified_by }}</div>
            </div>
        </div>

        <div class="slip-footer">
            <div class="note">Simpan slip ini sebagai bukti pembayaran Anda</div>
            <div class="slip-signature">
                <div class="sig-block">
                    <div>Siswa/Orang Tua</div>
                    <div class="sig-line"></div>
                    <div class="sig-name">{{ $student_name }}</div>
                </div>
                <div class="sig-block">
                    <div>Admin/Operator</div>
                    <div class="sig-line"></div>
                    <div class="sig-name">{{ $verified_by }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- SEKOLAH COPY -->
    <div class="slip-container">
        <div class="slip-header">
            @if(file_exists($school_logo))
                <img src="{{ $school_logo }}" alt="Logo" class="school-logo">
            @endif
            <h1>{{ $school_name }}</h1>
            <p>BUKTI PEMBAYARAN SPP</p>
        </div>

        <div class="slip-title">LEMBAR SEKOLAH</div>

        <div class="slip-content">
            <div class="slip-row">
                <div class="slip-label">No. Slip</div>
                <div class="slip-colon">:</div>
                <div class="slip-value">{{ $slip_number }}</div>
            </div>
            <div class="slip-row">
                <div class="slip-label">Tanggal</div>
                <div class="slip-colon">:</div>
                <div class="slip-value">{{ $slip_date }}</div>
            </div>
            <div class="slip-row">
                <div class="slip-label">Nama Siswa</div>
                <div class="slip-colon">:</div>
                <div class="slip-value">{{ $student_name }}</div>
            </div>
            <div class="slip-row">
                <div class="slip-label">ID Siswa</div>
                <div class="slip-colon">:</div>
                <div class="slip-value">{{ $student_id }}</div>
            </div>
            <div class="slip-row">
                <div class="slip-label">Kelas</div>
                <div class="slip-colon">:</div>
                <div class="slip-value">{{ $class }}</div>
            </div>
            <div class="slip-row">
                <div class="slip-label">Metode Pembayaran</div>
                <div class="slip-colon">:</div>
                <div class="slip-value">{{ $payment_method }}</div>
            </div>
        </div>

        <div class="slip-amount-box">
            <div class="slip-amount-label">JUMLAH PEMBAYARAN</div>
            <div class="slip-amount-value">Rp {{ $amount }}</div>
            <div class="slip-amount-text">{{ $amount_text }}</div>
        </div>

        <div class="slip-content">
            <div class="slip-row">
                <div class="slip-label">Status</div>
                <div class="slip-colon">:</div>
                <div class="slip-value">
                    <span class="status-label" style="background-color: {{ $status_color }}; color: white;">
                        {{ $status }}
                    </span>
                </div>
            </div>
            <div class="slip-row">
                <div class="slip-label">Diverifikasi Oleh</div>
                <div class="slip-colon">:</div>
                <div class="slip-value">{{ $verified_by }}</div>
            </div>
        </div>

        <div class="slip-footer">
            <div class="note">Arsip sekolah</div>
            <div class="slip-signature">
                <div class="sig-block">
                    <div>Siswa/Orang Tua</div>
                    <div class="sig-line"></div>
                    <div class="sig-name">{{ $student_name }}</div>
                </div>
                <div class="sig-block">
                    <div>Admin/Operator</div>
                    <div class="sig-line"></div>
                    <div class="sig-name">{{ $verified_by }}</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
