<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Rekap Presensi</title>

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
        font-size: 10px;
    }

    .tabelpresensi tr td {
        border: 1px solid #050505;
    }
  </style>
</head>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->
<body class="A4 landscape">
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
    <table class="tabelpresensi">
        <tr>
            <th rowspan="2">NPM</th>
            <th rowspan="2">NAMA MAHASISWA</th>
            <th colspan="31">Tanggal</th>
        </tr>
        <tr>
            <?php
            for($i=1; $i<=31; $i++){
            ?>
                <th>{{ $i }}</th>
            <?php
                }
            ?>
        </tr>
        @foreach ($rekap as $d)
        <tr>
            <td>{{ $d->npm }}</td>
            <td>{{ $d->nama_lengkap }}</td>

            <?php
            for($i=1; $i<=31; $i++){
                $tgl = "tgl_".$i;
                if(empty($d->tgl)){
                    $hadir = ['',''];
                }else{
                    $hadir = explode("-",$d->$tgl);
                }

            ?>
                <td>
                    <span style="color:{{ $hadir[0]>"08:00:00" ? "red" : "" }}">{{ $hadir[0] }}</span><br>
                    <span style="color:{{ $hadir[0]<"16:00:00" ? "red" : "" }}">{{ $hadir[0] }}</span>
                </td>
            <?php
                }
            ?>
        </tr>
        @endforeach
    </table>



    <table width="100%" style="margin-top: 100px">
        <tr style="text-align: center"; >
            <td colspan="6">.............., {{ date('d-m-Y') }}</td>
        </tr>
        <tr style="text-align: center"; >
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
