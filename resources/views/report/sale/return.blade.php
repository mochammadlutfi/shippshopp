<html>

<head>
    <title>Laporan Retur {{ \Carbon\Carbon::parse($mulai)->translatedFormat('d m Y') }} - {{ \Carbon\Carbon::parse($selesai)->translatedFormat('d m Y') }}</title>

    <link rel="stylesheet" href="/css/bootstrap.css">
</head>

<body>
    <div class="container">
        <table width="100%">
            <tr>
                <td width="100px" class="text-center">
                    <img src="/images/logo/logo.png" width="80pt"/>
                </td>
                <td class="text-center">
                    <h1 class="h4 text-center" style="font-size: 20pt;font-weight:bold;">TOKO KOMBET</h1>
                    <p class="mb-0" style="font-size: 14pt;margin-bottom:15px">Mahato, Tambusai Utara, Kab. Rokan Hulu, Riau</p>
                </td>
            </tr>
        </table>
        <hr/>
        <h2 class="h3 text-center" style="font-weight: bold; margin-top:0px">LAPORAN RETUR</h2>
        <h2 class="h4 text-center" style="font-weight: bold; margin-top:0px">
            Periode : {{ \Carbon\Carbon::parse($mulai)->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse($selesai)->translatedFormat('d F Y') }}
        </h2>
        <h2 class="h4 text-center" style="font-weight: bold; margin-top:0px">
            Status : {{ $status == "" ? "Semua Status" : $status }}
        </h2>
        <table class="table v-align-center table-bordered datatable w-100">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nomor</th>
                    <th>Nomor Pesanan</th>
                    <th>Pelanggan</th>
                    <th>Tanggal</th>
                    <th>Jumlah Produk</th>
                    <th>Total Pesanan</th>
                    @if($status == 'Semua')
                    <th>Status</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 1;
                @endphp
                @foreach ($data as $a)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $a->nomor }}</td>
                        <td>{{ $a->order->nomor }}</td>
                        <td>{{ $a->customer->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($a->date)->translatedFormat('d F Y') }}</td>
                        <td>{{ $a->lines_count }} Produk</td>
                        <td>Rp {{ number_format($a->order->grand_total,0,',','.') }}</td>
                        
                        @if($status == 'Semua')
                        <td>
                            @if ($a->status == 'pending')
                            Pending
                            @elseif($a->status == 'confirm')
                            Disetujui
                            @elseif($a->status == 'reject')
                            Ditolak
                            @endif
                        </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>

</html>