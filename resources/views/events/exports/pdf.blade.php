{{-- Lokasi File: resources/views/events/exports/pdf.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Event</title>
    <style>
        /* Styling dasar khusus untuk DomPDF */
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            padding: 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 12px;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #999;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Laporan Data Keseluruhan Event</h2>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="25%">Judul Event</th>
                <th width="15%">Kategori</th>
                <th width="20%">Tanggal Pelaksanaan</th>
                <th width="20%">Lokasi</th>
                <th width="15%">Biaya (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($events as $index => $event)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $event->title }}</td>
                    <td>{{ $event->eventCategory ? $event->eventCategory->name : '-' }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y') }} 
                        @if($event->end_date && $event->end_date != $event->start_date)
                            - {{ \Carbon\Carbon::parse($event->end_date)->format('d/m/Y') }}
                        @endif
                    </td>
                    <td>{{ $event->lokasi ?? '-' }}</td>
                    <td class="text-right">
                        {{ $event->registration_fee ? number_format($event->registration_fee, 0, ',', '.') : 'Gratis' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data event saat ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>