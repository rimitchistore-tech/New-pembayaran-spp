<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 3px solid #1F4E78;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .logo-section {
            flex: 1;
        }
        .logo {
            max-width: 80px;
            height: auto;
        }
        .school-info {
            flex: 2;
            padding-left: 20px;
        }
        .school-info h1 {
            font-size: 18px;
            color: #1F4E78;
            margin-bottom: 5px;
        }
        .school-info p {
            font-size: 12px;
            color: #666;
            margin: 2px 0;
        }
        .invoice-title {
            flex: 1;
            text-align: right;
        }
        .invoice-title h2 {
            font-size: 24px;
            color: #1F4E78;
            margin-bottom: 5px;
        }
        .invoice-number {
            font-size: 12px;
            color: #666;
        }
        .invoice-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .detail-block h3 {
            font-size: 12px;
            font-weight: bold;
            color: #1F4E78;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        .detail-block p {
            font-size: 11px;
            margin: 3px 0;
            color: #333;
        }
        .detail-block strong {
            font-weight: bold;
            display: block;
            font-size: 12px;
        }
        .payment-section {
            background-color: #f0f0f0;
            padding: 15px;
            border-left: 4px solid #1F4E78;
            margin-bottom: 20px;
            border-radius: 3px;
        }
        .payment-section h3 {
            font-size: 12px;
            font-weight: bold;
            color: #1F4E78;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        .payment-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .payment-item {
            font-size: 11px;
        }
        .payment-item label {
            color: #666;
            font-weight: bold;
            display: block;
            margin-bottom: 3px;
        }
        .payment-item .value {
            color: #333;
            font-size: 12px;
        }
        .amount-box {
            background-color: #1F4E78;
            color: white;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 20px;
        }
        .amount-box .label {
            font-size: 12px;
            text-transform: uppercase;
            opacity: 0.9;
            margin-bottom: 5px;
        }
        .amount-box .amount {
            font-size: 28px;
            font-weight: bold;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-badge.success {
            background-color: #28A745;
            color: white;
        }
        .status-badge.warning {
            background-color: #FFC107;
            color: #333;
        }
        .status-badge.info {
            background-color: #17A2B8;
            color: white;
        }
        .status-badge.danger {
            background-color: #DC3545;
            color: white;
        }
        .proof-section {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .proof-section h4 {
            font-size: 12px;
            color: #1F4E78;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .proof-image {
            max-width: 100%;
            height: auto;
            border: 1px solid #ddd;
            border-radius: 3px;
        }
        .footer {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #ddd;
            text-align: center;
        }
        .signature-block {
            font-size: 11px;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 30px;
            padding-top: 5px;
            min-height: 40px;
        }
        .signature-name {
            font-weight: bold;
            font-size: 11px;
            margin-top: 5px;
        }
        .notes {
            font-size: 10px;
            color: #666;
            margin-top: 20px;
            padding: 10px;
            background-color: #fffacd;
            border-left: 3px solid #FFC107;
            border-radius: 3px;
        }
        .notes strong {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th {
            background-color: #1F4E78;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 11px;
        }
        table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            font-size: 11px;
        }
        .generated-at {
            font-size: 9px;
            color: #999;
            text-align: right;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo-section">
                @if(file_exists($school_logo))
                    <img src="{{ $school_logo }}" alt="Logo" class="logo">
                @endif
            </div>
            <div class="school-info">
                <h1>{{ $school_name }}</h1>
                <p><strong>{{ $school_address }}</strong></p>
                <p>Telepon: {{ $school_phone }}</p>
                <p>Email: {{ $school_email }}</p>
            </div>
            <div class="invoice-title">
                <h2>INVOICE</h2>
                <div class="invoice-number">{{ $invoice_number }}</div>
            </div>
        </div>

        <!-- Invoice Details -->
        <div class="invoice-details">
            <div class="detail-block">
                <h3>Informasi Siswa</h3>
                <p><strong>{{ $student_name }}</strong></p>
                <p>ID Siswa: {{ $student_id }}</p>
                <p>Kelas: {{ $class }}</p>
                <p>Email: {{ $email }}</p>
            </div>
            <div class="detail-block">
                <h3>Informasi Orang Tua</h3>
                <p><strong>{{ $parent_name }}</strong></p>
                <p>Telepon: {{ $phone }}</p>
                <p>Email: {{ $email }}</p>
            </div>
        </div>

        <!-- Payment Information -->
        <div class="payment-section">
            <h3>Informasi Pembayaran</h3>
            <div class="payment-details">
                <div class="payment-item">
                    <label>Metode Pembayaran:</label>
                    <div class="value">{{ $payment_method }}</div>
                </div>
                <div class="payment-item">
                    <label>No. Referensi:</label>
                    <div class="value">{{ $reference }}</div>
                </div>
                <div class="payment-item">
                    <label>Tanggal Pembayaran:</label>
                    <div class="value">{{ $invoice_date }}</div>
                </div>
                <div class="payment-item">
                    <label>Status:</label>
                    <div class="value">
                        <span class="status-badge {{ $status }}">{{ $status_label }}</span>
                    </div>
                </div>
                <div class="payment-item">
                    <label>Keterangan:</label>
                    <div class="value">{{ $description }}</div>
                </div>
                <div class="payment-item">
                    <label>Diverifikasi Oleh:</label>
                    <div class="value">{{ $verified_by }} ({{ $verified_by_position }})</div>
                </div>
            </div>
        </div>

        <!-- Amount Box -->
        <div class="amount-box">
            <div class="label">Jumlah Pembayaran</div>
            <div class="amount">Rp {{ $amount }}</div>
        </div>

        <!-- Proof File -->
        @if($proof_file)
        <div class="proof-section">
            <h4>Bukti Pembayaran</h4>
            <img src="{{ $proof_file }}" alt="Bukti Pembayaran" class="proof-image">
        </div>
        @endif

        <!-- Footer Signature -->
        <div class="footer">
            <div class="signature-block">
                <div>Orang Tua/Wali</div>
                <div class="signature-line"></div>
                <div class="signature-name">{{ $parent_name }}</div>
            </div>
            <div class="signature-block">
                <div>Admin/Operator</div>
                <div class="signature-line"></div>
                <div class="signature-name">{{ $verified_by }}</div>
            </div>
            <div class="signature-block">
                <div>Kepala Sekolah</div>
                <div class="signature-line"></div>
                <div class="signature-name">_________________</div>
            </div>
        </div>

        <!-- Notes -->
        <div class="notes">
            <strong>Catatan:</strong>
            <ul style="margin-left: 15px; margin-top: 5px;">
                <li>Invoice ini adalah bukti pembayaran resmi dari {{ $school_name }}</li>
                <li>Mohon simpan invoice ini sebagai arsip pembayaran Anda</li>
                <li>Untuk pertanyaan, hubungi admin sekolah melalui {{ $school_email }}</li>
            </ul>
        </div>

        <div class="generated-at">
            Dokumen dibuat pada: {{ $generated_at }}
        </div>
    </div>
</body>
</html>
