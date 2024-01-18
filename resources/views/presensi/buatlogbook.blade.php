@extends('layouts.presensi')
@section('header')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<!-- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Form LogBook</div>
    <div class="right"></div>
</div>
<!-- * App Header -->
@endsection

@section('content')
<div class="row" style="margin-top: 70px">
    <div class="col">
        <form method="POST" action="/presensi/storelogbook" id="frmlogbook">
            @csrf
            <div class="form-group">
                <input type="text" id="tgl_buat" name="tgl_buat" class="form-control date" placeholder="Tanggal">
            </div>
            <div class="form-group">
                <input type="text" id="hari_ke" name="hari_ke" class="form-control" placeholder="Hari Ke">
            </div>

            {{-- <div class="form-group">
                <input type="file" name="foto" class="form-control">
            </div> --}}
            <div class="form-group">
                <textarea name="catatan" id="catatan" cols="30" rows="5" class="form-control" placeholder="Catatan"></textarea>
            </div>
            <div class="form-group">
                <button class="btn btn-primary w-100">Kirim</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('myscript')
    <script>
        $('.date').datepicker({
        multidate: true,
            format: 'yyyy-mm-dd'
        });
        $("#frmlogbook").submit(function(){
            var tgl_buat = $('#tgl_buat').val();
            var hari_ke = $('#hari_ke').val();
            var catatan = $('#catatan').val();

            if (tgl_buat == "") {
                Swal.fire({
                    title: 'Oops !',
                    text: 'Tanggal Harus Diisi',
                    icon: 'warning',
                    });
                return false;
            } else if (hari_ke == "") {
                Swal.fire({
                    title: 'Oops !',
                    text: 'Hari Harus Diisi',
                    icon: 'warning',
                    });
                return false;
            } else if (catatan == "") {
                Swal.fire({
                    title: 'Oops !',
                    text: 'Catatan Harus Diisi',
                    icon: 'warning',
                    });
                return false;
            }
        })
    </script>
@endpush
