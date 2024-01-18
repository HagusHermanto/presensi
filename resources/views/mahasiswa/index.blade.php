@extends('layouts.admin.admin')
@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
      <div class="row g-2 align-items-center">
        <div class="col">
          <!-- Page pre-title -->
          <h2 class="page-title">
            Data Mahasiswa
          </h2>
        </div>
      </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            @if (Session::get('success'))
                                <div class="alert alert-success">
                                    {{Session::get('success')}}
                                </div>
                            @endif

                            @if (Session::get('warning'))
                                <div class="alert alert-warning">
                                    {{Session::get('warning')}}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <a href="#" class="btn btn-primary" id="btntambahmahasiswa">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M12 5l0 14"></path>
                                    <path d="M5 12l14 0"></path>
                                 </svg>
                                 Tambah Data</a>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="/mahasiswa" method="GET">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <input type="text" name="nama_mahasiswa" id="nama_mahasiswa" class="form-control" placeholder="Nama Mahasiswa" value="{{ Request('nama_mahasiswa') }}">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <button style="submit" class="btn btn-primary">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-search" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                                    <path d="M21 21l-6 -6"></path>
                                                 </svg>
                                                 Cari
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>NPM</th>
                                        <th>Nama</th>
                                        <th>Jabatan</th>
                                        <th>No. HP</th>
                                        <th>Foto</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($mahasiswa as $d)
                                    @php
                                        $path = Storage::url('uploads/mahasiswa/'.$d->foto);
                                    @endphp
                                        <tr>
                                            <td>{{$loop->iteration + $mahasiswa->firstItem() -1}}</td>
                                            <td>{{$d->npm}}</td>
                                            <td>{{$d->nama_lengkap}}</td>
                                            <td>{{$d->jabatan}}</td>
                                            <td>{{$d->no_hp}}</td>
                                            <td>
                                                @if (empty($d->foto))
                                                <img src="{{ asset('assets/img/images.png') }}" class="avatar" alt="">
                                                @else
                                                <img src="{{ url($path) }}" class="avatar" alt="">
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="#" class="edit btn btn-warning btn-sm" npm="{{ $d->npm }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path>
                                                        <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path>
                                                        <path d="M16 5l3 3"></path>
                                                        </svg>
                                                    </a>
                                                    <form action="/mahasiswa/{{ $d->npm }}/delete" method="POST" style="margin-left:5px">
                                                        @csrf
                                                        <button class="btn btn-danger btn-sm delete-confirm">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                <path d="M4 7l16 0"></path>
                                                                <path d="M10 11l0 6"></path>
                                                                <path d="M14 11l0 6"></path>
                                                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                                                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
                                                             </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $mahasiswa->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>

{{-- Modals --}}
<div class="modal modal-blur fade" id="modal-inputmahasiswa" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Data Mahasiswa</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="/mahasiswa/store" method="POST" id="frmmahasiswa" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <div class="input-icon mb-3">
                            <span class="input-icon-addon">
                              <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-dialpad" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M4 3h2a1 1 0 0 1 1 1v2a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1v-2a1 1 0 0 1 1 -1z"></path>
                                <path d="M18 3h2a1 1 0 0 1 1 1v2a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1v-2a1 1 0 0 1 1 -1z"></path>
                                <path d="M11 3h2a1 1 0 0 1 1 1v2a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1v-2a1 1 0 0 1 1 -1z"></path>
                                <path d="M4 10h2a1 1 0 0 1 1 1v2a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1v-2a1 1 0 0 1 1 -1z"></path>
                                <path d="M18 10h2a1 1 0 0 1 1 1v2a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1v-2a1 1 0 0 1 1 -1z"></path>
                                <path d="M11 10h2a1 1 0 0 1 1 1v2a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1v-2a1 1 0 0 1 1 -1z"></path>
                                <path d="M11 17h2a1 1 0 0 1 1 1v2a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1v-2a1 1 0 0 1 1 -1z"></path>
                             </svg>
                            </span>
                            <input type="text" name="npm" id="npm" value="" class="form-control" placeholder="NPM">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="input-icon mb-3">
                            <span class="input-icon-addon">
                              <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>
                                <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                             </svg>
                            </span>
                            <input type="text" name="nama_lengkap" id="nama_lengkap" value="" class="form-control" placeholder="Nama Lengkap">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <div class="form-label"></div>
                            <select class="form-select" name="jabatan">
                              <option value="#">---Pilih---</option>
                              <option value="Mahasiswa">Mahasiswa</option>
                              <option value="Dosen">Dosen</option>
                              <option value="Ketua Prodi">Ketua Prodi</option>
                            </select>
                          </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="input-icon mb-3">
                            <span class="input-icon-addon">
                              <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-phone" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2"></path>
                             </svg>
                            </span>
                            <input type="text" name="no_hp" id="no_hp" value="" class="form-control" placeholder="No. HP">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <input type="file" name="foto" class="form-control">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <div class="form-group">
                            <button class="btn btn-primary w-100">Simpan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>

  {{-- Edit Data --}}
  <div class="modal modal-blur fade" id="modal-editmahasiswa" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Data Mahasiswa</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="loadeditform">

        </div>
      </div>
    </div>
  </div>
@endsection

@push('myscript')
<script>
    $(function(){
        $("#btntambahmahasiswa").click(function() {
            $("#modal-inputmahasiswa").modal("show");
        });

        $(".edit").click(function() {
            var npm = $(this).attr('npm');
            $.ajax({
                type: 'POST'
                , url: '/mahasiswa/edit'
                , cache: false
                , data: {
                    _token: "{{ csrf_token() }}"
                    , npm: npm
                }
                , success: function(respond){
                    $("#loadeditform").html(respond);
                }
            });
            $("#modal-editmahasiswa").modal("show");
        });

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

        $("#frmmahasiswa").submit(function() {
            var npm = $("#npm").val();
            var nama_lengkap = $("#nama_lengkap").val();
            var jabatan = $("#jabatan").val();
            var no_hp = $("#no_hp").val();

            if (npm == "") {
                Swal.fire({
                title: 'Warning!',
                text: 'NPM Harus Diisi !',
                icon: 'warning',
                confirmButtonText: 'OK'
                }).then((result) => {
                    $("#npm").focus();
                })
                return false;
            } else if (nama_lengkap == "") {
                Swal.fire({
                title: 'Warning!',
                text: 'Nama Lengkap Harus Diisi !',
                icon: 'warning',
                confirmButtonText: 'OK'
                }).then((result) => {
                    $("#nama_lengkap").focus();
                });
                return false;
            } else if (jabatan == "") {
                Swal.fire({
                title: 'Warning!',
                text: 'Jabatan Harus Diisi !',
                icon: 'warning',
                confirmButtonText: 'OK'
                }).then((result) => {
                    $("#jabatan").focus();
                });
                return false;
            } else if (no_hp == "") {
                Swal.fire({
                title: 'Warning!',
                text: 'No. HP Harus Diisi !',
                icon: 'warning',
                confirmButtonText: 'OK'
                }).then((result) => {
                    $("#no_hp").focus();
                });
                return false;
            }
        })
    });
</script>
@endpush
