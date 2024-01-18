<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Mahasiswa::query();
        $query->select('mahasiswa.*');
        $query->orderBy('nama_lengkap');
        if (!empty($request->nama_mahasiswa)) {
            $query->where('nama_lengkap', 'like', '%' . $request->nama_mahasiswa . '%');
        }
        $mahasiswa = $query->paginate(10);

        return view('mahasiswa.index', compact('mahasiswa'));
    }

    public function store(Request $request)
    {
        $npm = $request->npm;
        $nama_lengkap = $request->nama_lengkap;
        $jabatan = $request->jabatan;
        $no_hp = $request->no_hp;
        $password = Hash::make('123456');

        if ($request->hasFile('foto')) {
            $foto = $npm . "." . $request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = null;
        }

        try {
            $data = [
                'npm' => $npm,
                'nama_lengkap' => $nama_lengkap,
                'jabatan' => $jabatan,
                'no_hp' => $no_hp,
                'foto' => $foto,
                'password' => $password
            ];
            $simpan = DB::table('mahasiswa')->insert($data);
            if ($simpan) {
                if ($request->hasFile('foto')) {
                    $folderPath = "public/uploads/mahasiswa/";
                    $request->file('foto')->storeAs($folderPath, $foto);
                }
                return Redirect::back()->with(['success' => 'Data Berhasil Dismpan']);
            }
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Data Gagal Dismpan']);
        }
    }

    public function edit(Request $request)
    {
        $npm = $request->npm;
        $mahasiswa = DB::table('mahasiswa')->where('npm', $npm)->first();
        return view('mahasiswa.edit', compact('mahasiswa'));
    }

    public function update($npm, Request $request)
    {
        $npm = $request->npm;
        $nama_lengkap = $request->nama_lengkap;
        $jabatan = $request->jabatan;
        $no_hp = $request->no_hp;
        $password = Hash::make('123456');
        $old_foto = $request->old_foto;

        if ($request->hasFile('foto')) {
            $foto = $npm . "." . $request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = $old_foto;
        }

        try {
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'jabatan' => $jabatan,
                'no_hp' => $no_hp,
                'foto' => $foto,
                'password' => $password
            ];
            $update = DB::table('mahasiswa')->where('npm', $npm)->update($data);
            if ($update) {
                if ($request->hasFile('foto')) {
                    $folderPath = "public/uploads/mahasiswa/";
                    $folderPathOld = "public/uploads/mahasiswa/" . $old_foto;
                    Storage::delete($folderPathOld);
                    $request->file('foto')->storeAs($folderPath, $foto);
                }
                return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
            }
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Data Gagal Diupdate']);
        }
    }

    public function delete($npm)
    {
        $delete = DB::table('mahasiswa')->where('npm', $npm)->delete();
        if ($delete) {
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal DiHapus']);
        }
    }
}
