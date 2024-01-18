@extends('layouts.presensi')
@section('header')
<!-- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Data LogBook</div>
    <div class="right"></div>
</div>
<!-- * App Header -->
@endsection

@section('content')
<div class="row" style="margin-top: 70px">
    <div class="col">
        @php
            $messagesuccess = Session::get('success');
            $messageerror = Session::get('error');
        @endphp
        @if(session::get('success'))
        <div class="alert alert-success">
            {{ $messagesuccess }}
        </div>
        @endif
        @if(session::get('error'))
        <div class="alert alert-danger">
            {{ $messageerror }}
        </div>
        @endif
    </div>
</div>

<div class="row">
    <div class="col">
        @foreach ($datalogbook as $d)
        <ul class="listview image-listview">
            <li>
                <div class="item">
                    <div class="in">
                        <div>
                            <b>{{ date("d-m-Y",strtotime($d->tgl_buat)) }} (Hari ke: {{ $d->hari_ke}})</b><br>
                            <small class="text-muted">{{ Str::limit($d->catatan, '35') }}</small>
                        </div>
                        <div class="mt-2" style="display: flex; align-items: center;">
                            <a href="/presensi/editlogbook" class="btn btn-primary btn-sm" style="margin-right: 5px;">
                                <ion-icon name="pencil-outline" style="font-size: 16px;"></ion-icon>
                            </a>
                            <form class="" action="/presensi/{{ $d->id }}/deletelogbook" method="POST">
                                @csrf
                                <button class="btn btn-danger btn-sm delete-confirm">
                                    <ion-icon name="trash-outline" style="font-size: 16px;"></ion-icon>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
        @endforeach
    </div>
</div>

<div class="fab-button bottom-right" style="margin-bottom: 70px">
    <a href="/presensi/buatlogbook" class="fab">
        <ion-icon name="add-outline"></ion-icon>
    </a>
</div>
@endsection

@push('myscript')
<script>
    $(function(){
        $(".delete-confirm").click(function(e) {
        var form = $(this).closest('form');
        e.preventDefault();
        Swal.fire({
        title: 'Kamu Yakin Delete Data ini ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Yakin Delete!'
        }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
            Swal.fire(
            'Deleted!',
            'Data berhasil dihapus',
            'success'
            )
        }
        })
    });
});
</script>
@endpush

