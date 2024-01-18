<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Laporan Presensi</title>

  <!-- Normalize or reset CSS with your favorite library -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">

  <!-- Load paper.css for happy printing -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

  <!-- Set page size here: A5, A4 or A3 -->
  <!-- Set also "landscape" if you need -->
  <style>
    @page {
        size: A4;
        margin: 3cm 4cm 3cm 3cm; /* top, right, bottom, left */
    }

    .tabledatamahasiswa {
        margin-top: 40px;
    }

    .tabledatamahasiswa td {
        padding: 5px;
    }
    .tabelpresensi {
        width: 100%;
        margin-top: 20px;
        border-collapse: collapse;
    }
    .tabelpresensi tr th{
        border: 1px solid #050505;
        padding: 8px;
    }

    .tabelpresensi tr td {
        border: 1px solid #050505;
    }
  </style>
</head>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->
<body class="A4">
<?php
function selisih($jam_masuk, $jam_keluar)
        {
            list($h, $m, $s) = explode(":", $jam_masuk);
            $dtAwal = mktime($h, $m, $s, "1", "1", "1");
            list($h, $m, $s) = explode(":", $jam_keluar);
            $dtAkhir = mktime($h, $m, $s, "1", "1", "1");
            $dtSelisih = $dtAkhir - $dtAwal;
            $totalmenit = $dtSelisih / 60;
            $jam = explode(".", $totalmenit / 60);
            $sisamenit = ($totalmenit / 60) - $jam[0];
            $sisamenit2 = $sisamenit * 60;
            $jml_jam = $jam[0];
            return $jml_jam . ":" . round($sisamenit2);
        }
?>
  <!-- Each sheet element should have the class "sheet" -->
  <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
  <section class="sheet padding-10mm">

    <table style="width: 100%">
        <tr>
            <td style="vertical-align: top;">
                <img src="{{ asset('assets/img/Logo_Kampus.png') }}" width="70" height="70" alt="">
            </td>
            <td style="text-align: right;">
                <h4 style="margin: 0;">
                    PRODI TEKNOLOGI REKAYASA KOMPUTER JARINGAN (TRKJ)
                </h4>
                <h5 style="margin: 0;">
                    FAKULTAS TEKNOLOGI INDUSTRI UNIVERSITAS BUNG HATTA
                </h5>
                <h6 style="margin: 0;">
                    Kampus III: Jl. Gajahmada No. 19 Olo Naggalo Padang Telp. (0751) 7054257
                </h6>

            </td>
        </tr>
    </table>
    <hr style="border-top: 2px solid #050505; margin: 5px 0;">
    <table style="width: 100%">
        <tr>
            <td style="text-align: center;">
                <h5 style="margin: 0;">PERIODE BULAN: {{ strtoupper($namabulan[$bulan]) }} {{ $tahun }}</h5>
            </td>
        </tr>
    </table>

    <table class="tabledatamahasiswa">
        <tr>
            <td rowspan="4">
                @php
                    $path = Storage::url('uploads/mahasiswa/'.$mahasiswa->foto);
                @endphp
                <img src="{{ url($path) }}" alt="" width="80px;" height="100px;">
            </td>
        </tr>
        <tr>
            <td>NPM</td>
            <td>:</td>
            <td>{{ $mahasiswa->npm}}</td>
        </tr>
        </tr>
        <tr>
            <td>Nama Mahasiswa</td>
            <td>:</td>
            <td>{{ $mahasiswa->nama_lengkap}}</td>
        </tr>
        <tr>
            <td>Telepon</td>
            <td>:</td>
            <td>{{ $mahasiswa->no_hp}}</td>
        </tr>
    </table>

    <table class="tabelpresensi">
        <tr>
            <th>No.</th>
            <th>Tanggal</th>
            <th>Jam Masuk</th>
            <th>Foto</th>
            <th>Jam Pulang</th>
            <th>Foto</th>
            <th>Keterangan</th>
            <th>Jml Jam</th>
        </tr>
        @foreach ($presensi as $d )
        @php
            $path_in = Storage::url('uploads/presensi/'.$d->foto_in);
            $path_out = Storage::url('uploads/presensi/'.$d->foto_out);
            $jamterlambat = selisih('08:00:00',$d->jam_in);
        @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ date("d-m-Y",strtotime($d->tgl_presensi)) }}</td>
                <td>{{ $d->jam_in }}</td>
                <td><img src="{{ url($path_in) }}" alt="" width="50px;" height="50px;"></td>
                <td>{{ $d->jam_out !== null ? $d->jam_out : 'Belum Absen' }}</td>
                <td>
                    @if ($d->jam_out !== null)
                    <img src="{{ url($path_out) }}" alt="" width="50px;" height="50px;">
                    @else
                    No Photo
                    @endif
                </td>
                <td>
                    @if ($d->jam_in > '08:00')
                        Terlambat {{ $jamterlambat }}
                    @else
                    Tepat Waktu
                    @endif
                </td>
                <td>
                    @if ($d->jam_out !== null)
                    @php
                        $jmljamkerja = selisih($d->jam_in, $d->jam_out);
                    @endphp
                @else
                    @php
                        $jmljamkerja = 0;
                    @endphp
                @endif
                {{ $jmljamkerja }}
                </td>
            </tr>
        @endforeach
    </table>

    <table width="100%" style="margin-top: 100px">
        <tr style="text-align: center"; >
            <td colspan="6">.............., {{ date('d-m-Y') }}</td>
        </tr>
        <tr style="text-align: center"; >
            <td>
                <h5>Pembimbing Lapangan</h5>
                <hr style="width: 150px; margin-top: 120px">
            </td>
            <td>
                <h5>Dosen Pembimbing</h5>
                <hr style="width: 150px; margin-top: 120px">
            </td>
            <td>
                <h5>Ketua Program Studi<br>
                    Teknologi Rekayasa Komputer Jaringan</h5>
                    <hr style="width: 150px; margin-top: 100px ">
            </td>
        </tr>
    </table>



</section>

</body>

</html>
