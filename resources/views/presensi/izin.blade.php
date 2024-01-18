@extends('layouts.presensi')
@section('header')
<!-- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Data Izin / Sakit</div>
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
    @foreach ($dataizin as $d)
    <ul class="listview image-listview">
        <li>
            <div class="item">
                <div class="in">
                    <div>
                        <b>{{ date("d-m-Y",strtotime($d->tgl_izin)) }} ({{ $d->status == "s" ? "Sakit" : "Izin" }})</b><br>
                        <small class="text-muted">{{ $d->keterangan }}</small>
                    </div>
                    @if ($d->status_aproved == 0)
                    <span class="badge badge-warning ml-auto">Pending</span>
                    @elseif($d->status_aproved == 1)
                    <span class="badge badge-success ml-auto">Diterima</span>
                    @elseif($d->status_aproved == 2)
                    <span class="badge badge-danger ml-auto">Ditolak</span>
                    @endif
                </div>
            </div>
        </li>
    </ul>
    @endforeach
</div>
</div>

<div class="fab-button bottom-right" style="margin-bottom: 70px">
    <a href="/presensi/buatizin" class="fab">
        <ion-icon name="pencil-outline"></ion-icon>
    </a>
</div>
@endsection
