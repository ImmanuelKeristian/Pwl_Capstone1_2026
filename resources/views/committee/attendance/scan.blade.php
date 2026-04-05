@extends('layouts.master')
@section('title', 'Scan Kehadiran')

@section('web-content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">
            <a href="{{ route('committee.attendance.index') }}">Kelola Kehadiran</a> /
        </span> 
        Scan QR Code untuk Sesi: {{ $session->title }}
    </h4>

    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-body text-center">
                    <p class="text-muted">Arahkan kamera ke QR Code peserta.</p>
                    <div id="qr-reader" style="width: 100%; max-width: 500px; margin: auto;"></div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
             <div class="card">
                <div class="card-header"><h5 class="mb-0">Hasil Scan Terakhir</h5></div>
                <div class="card-body" id="scan-result-info">
                    <p class="text-muted text-center">Menunggu hasil scan...</p>
                </div>
             </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Kita akan menggunakan library html5-qrcode --}}
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    // Variabel penanda untuk mencegah scan ganda
    let isProcessing = false;

    function onScanSuccess(decodedText, decodedResult) {
        // Jika sedang memproses, abaikan scan baru
        if (isProcessing) {
            return;
        }

        // Set penanda menjadi true untuk memblokir scan berikutnya
        isProcessing = true;

        let resultDiv = document.getElementById('scan-result-info');
        resultDiv.innerHTML = `<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div><p>Memproses...</p></div>`;

        // Kirim data ke backend menggunakan fetch API
        fetch("{{ route('committee.attendance.process') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                qr_code: decodedText,
                session_id: {{ $session->id }}
            })
        })
        .then(response => response.json())
        .then(data => {
            let alertClass = data.success ? 'alert-success' : 'alert-danger';
            resultDiv.innerHTML = `<div class="alert ${alertClass} text-center"><strong>${data.message}</strong></div>`;
            // Reset penanda dan lanjutkan scan setelah beberapa detik
            setTimeout(() => { isProcessing = false; }, 2500);
        })
        .catch(error => {
            console.error('Error:', error);
            resultDiv.innerHTML = `<div class="alert alert-danger text-center"><strong>Terjadi kesalahan.</strong></div>`;
            // Reset penanda dan lanjutkan scan setelah beberapa detik
            setTimeout(() => { isProcessing = false; }, 2500);
        });
    }

    function onScanFailure(error) {
        // Abaikan error jika QR code tidak ditemukan, karena ini terjadi terus menerus
    }

    let html5QrcodeScanner = new Html5QrcodeScanner(
        "qr-reader", 
        { fps: 10, qrbox: {width: 250, height: 250} }, 
        false // verbose
    );
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
</script>
@endpush