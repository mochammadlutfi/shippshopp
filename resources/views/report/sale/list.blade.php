<html>

<head>
    <title>Laporan Penjualan {{ \Carbon\Carbon::parse($mulai)->translatedFormat('d m Y') }} - {{ \Carbon\Carbon::parse($selesai)->translatedFormat('d m Y') }}</title>

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
                    <h1 class="h4 text-center" style="font-size: 20pt;font-weight:bold;">Shipp Shopp Vape Store</h1>
                    <p class="mb-0" style="font-size: 14pt;margin-bottom:15px">Jl. Raya Binong No.6, Binong, Kec. Curug, Kabupaten Tangerang, Banten 15810</p>
                </td>
            </tr>
        </table>
        <hr/>
        <h2 class="h3 text-center" style="font-weight: bold; margin-top:0px">LAPORAN PENJUALAN</h2>
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
                    <th>Pelanggan</th>
                    <th>Tanggal</th>
                    <th>Jumlah Produk</th>
                    <th>Total Belanja</th>
                    {{-- <th>Biaya Kirim</th> --}}
                    <th>Total Final</th>
                    <th>Status</th>
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
                        <td>{{ $a->customer->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($a->date)->translatedFormat('d F Y') }}</td>
                        <td>{{ $a->lines_count }} Produk</td>
                        <td>Rp {{ number_format($a->total,0,',','.') }}</td>
                        {{-- <td>Rp {{ number_format($a->shipping_cost,0,',','.') }}</td> --}}
                        <td>Rp {{ number_format($a->grand_total,0,',','.') }}</td>
                        <td>
                            @if ($a->state == 'pending')
                            Pending
                            @elseif($a->state == 'process')
                            Diproses
                            @elseif($a->state == 'shipped')
                            Dikirim
                            @elseif($a->state == 'done')
                            Selesai
                            @elseif($a->state == 'cancel')
                            Batal
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>

</html>