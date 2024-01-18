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
    <div class="pageTitle">Form Izin</div>
    <div class="right"></div>
</div>
<!-- * App Header -->
@endsection

@section('content')
<div class="row" style="margin-top: 70px">
    <div class="col">
        <form method="POST" action="/presensi/storeizin" id="frmizin">
            @csrf
            <div class="form-group">
                <input type="text" id="tgl_izin" name="tgl_izin" class="form-control date" placeholder="Tanggal">
            </div>
            <div class="from-group">
                <select name="status" id="status" class="form-control" placeholder="Izin / Sakit">
                    <option value="#">Izin / Sakit</option>
                    <option value="i">Izin</option>
                    <option value="s">Sakit</option>
                </select>
            </div><br>
            <div class="form-group">
                <textarea name="keterangan" id="keterangan" cols="30" rows="5" class="form-control" placeholder="Keterangan"></textarea>
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
        $("#frmizin").submit(function(){
            var tgl_izin = $('#tgl_izin').val();
            var status = $('#status').val();
            var keterangan = $('#keterangan').val();

            if (tgl_izin == "") {
                Swal.fire({
                    title: 'Oops !',
                    text: 'Tanggal Harus Diisi',
                    icon: 'warning',
                    });
                return false;
            } else if (status == "") {
                Swal.fire({
                    title: 'Oops !',
                    text: 'Status Harus Diisi',
                    icon: 'warning',
                    });
                return false;
            } else if (keterangan == "") {
                Swal.fire({
                    title: 'Oops !',
                    text: 'Keterangan Harus Diisi',
                    icon: 'warning',
                    });
                return false;
            }
        })
    </script>
@endpush
