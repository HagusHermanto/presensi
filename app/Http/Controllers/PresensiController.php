<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class PresensiController extends Controller
{
    public function create()
    {
        $hariini = date("Y-m-d");
        $npm = Auth::guard('mahasiswa')->user()->npm;
        $cek = DB::table("presensi")->where('tgl_presensi', $hariini)->where('npm', $npm)->count();
        return view('presensi.create', compact('cek'));
    }
    public function store(Request $request)
    {
        $npm = Auth::guard('mahasiswa')->user()->npm;
        $tgl_presensi = date("Y-m-d");
        $jam = date("H:i:s");
        $latitudekantor = -0.9082482167795595;  // ini bisa di hidden jika untuk magang, karena ini titik kantor
        $longitudekantor = 100.36469037979927;  // ini bisa di hidden jika untuk magang, karena ini titik kantor
        $lokasi = $request->lokasi;
        $lokasiuser = explode(",", $lokasi);
        $latitudeuser = $lokasiuser[0];
        $longitudeuser = $lokasiuser[1];
        $jarak = $this->distance($latitudekantor, $longitudekantor, $latitudeuser, $longitudeuser);
        $radius = round($jarak["meters"]);

        $cek = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('npm', $npm)->count();

        if ($cek > 0) {
            $ket = "out";
        } else {
            $ket = "in";
        }
        $image = $request->image;
        $folderPath = "public/uploads/presensi/";
        $formatName = $npm . "-" . $tgl_presensi . "-" . $ket;
        $image_parts = explode(";base64", $image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = $formatName . ".png";
        $file = $folderPath . $fileName;


        if ($radius > 50) {
            echo "error|Maaf Anda Berada Diluar Radius, Jarak anda " . $radius . " meter dari Kantor|radius";
        } else {
            if ($cek > 0) {
                $data_pulang = [
                    'jam_out' => $jam,
                    'foto_out' => $fileName,
                    'lokasi_out' => $lokasi,
                ];
                $update = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('npm', $npm)->update($data_pulang);

                if ($update) {
                    echo "success|Terimakasih, Hati-hati dijalan|out";
                    Storage::put($file, $image_base64);
                } else {
                    echo "error|Maaf gagal presensi, Harap ulang kembali|out";
                }
            } else {
                $data = [
                    'npm' => $npm,
                    'tgl_presensi' => $tgl_presensi,
                    'jam_in' => $jam,
                    'foto_in' => $fileName,
                    'lokasi_in' => $lokasi
                ];
                $simpan = DB::table('presensi')->insert($data);
                if ($simpan) {
                    echo "success|Terimakasih, Selamat Bekerja|in";
                    Storage::put($file, $image_base64);
                } else {
                    echo "error|Maaf gagal presensi, Harap ulang kembali|in";
                }
            }
        }
    }

    // ini bisa di hidden jika untuk magang, karena ini titik kantor
    //Menghitung Jarak
    function distance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return compact('meters');
    }

    public function editprofile()
    {
        $npm = Auth::guard('mahasiswa')->user()->npm;
        $mahasiswa = DB::table('mahasiswa')->where('npm', $npm)->first();
        return view('presensi.editprofile', compact('mahasiswa'));
    }

    public function updateprofile(Request $request)
    {
        $npm = Auth::guard('mahasiswa')->user()->npm;
        $nama_lengkap = $request->nama_lengkap;
        $no_hp = $request->no_hp;
        $password = Hash::make($request->password);
        $mahasiswa = DB::table('mahasiswa')->where('npm', $npm)->first();
        if ($request->hasFile('foto')) {
            $foto = $npm . "." . $request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = $mahasiswa->foto;
        }

        if (empty($request->password)) {
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'foto' => $foto
            ];
        } else {
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'password' => $password,
                'foto' => $foto
            ];
        }

        $update = DB::table('mahasiswa')->where('npm', $npm)->update($data);
        if ($update) {
            if ($request->hasFile('foto')) {
                $folderPath = "public/uploads/mahasiswa/";
                $request->file('foto')->storeAs($folderPath, $foto);
            }
            return Redirect::back()->with(['success' => 'Data Berhasil Di Update']);
        } else {
            return Redirect::back()->with(['error' => 'Data gagal Di Update']);
        }
    }

    public function histori()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

        return view('presensi.histori', compact('namabulan'));
    }

    public function gethistori(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $npm = Auth::guard('mahasiswa')->user()->npm;

        $histori = DB::table('presensi')
            ->whereRaw('MONTH(tgl_presensi)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_presensi) ="' . $tahun . '"')
            ->where('npm', $npm)
            ->orderBy('tgl_presensi')
            ->get();

        return view('presensi.gethistori', compact('histori'));
    }

    // Izin
    public function izin()
    {
        $npm = Auth::guard('mahasiswa')->user()->npm;
        $dataizin = DB::table('pengajuan_izin')->where('npm', $npm)->get();
        return view('presensi.izin', compact('dataizin'));
    }

    public function buatizin()
    {
        return view('presensi.buatizin');
    }

    public function storeizin(Request $request)
    {
        $npm = Auth::guard('mahasiswa')->user()->npm;
        $tgl_izin = $request->tgl_izin;
        $status = $request->status;
        $keterangan = $request->keterangan;

        $data = [
            'npm' => $npm,
            'tgl_izin' => $tgl_izin,
            'status' => $status,
            'keterangan' => $keterangan
        ];

        $simpan = DB::table('pengajuan_izin')->insert($data);

        if ($simpan) {
            return redirect('/presensi/izin')->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return redirect('/presensi/izin')->with(['error' => 'Data Gagal Disimpan']);
        }
    }

    // Logbook
    public function logbook()
    {
        $npm = Auth::guard('mahasiswa')->user()->npm;
        $datalogbook = DB::table('logbook')->where('npm', $npm)->get();
        return view('presensi.logbook', compact('datalogbook'));
    }

    public function buatlogbook()
    {
        return view('presensi.buatlogbook');
    }

    public function storelogbook(Request $request)
    {
        $npm = Auth::guard('mahasiswa')->user()->npm;
        $tgl_buat = $request->tgl_buat;
        $hari_ke = $request->hari_ke;
        // $foto = $request->foto;
        $catatan = $request->catatan;

        $data = [
            'npm' => $npm,
            'tgl_buat' => $tgl_buat,
            'hari_ke' => $hari_ke,
            // 'foto' => $foto,
            'catatan' => $catatan
        ];

        $simpan = DB::table('logbook')->insert($data);

        if ($simpan) {
            return redirect('/presensi/logbook')->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return redirect('/presensi/logbook')->with(['error' => 'Data Gagal Disimpan']);
        }
    }

    public function editlogbook(Request $request)
    {
        $npm = Auth::guard('mahasiswa')->user()->npm;
        $logbook = DB::table('logbook')->where('npm', $npm)->first();
        return view('presensi.editlogbook', compact('logbook'));
    }

    public function updatelogbook(Request $request)
    {
    }

    public function deletelogbook($id)
    {
        $delete = DB::table('logbook')->where('id', $id)->delete();
        if ($delete) {
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal DiHapus']);
        }
    }

    // Monitoring
    public function monitoring()
    {
        return view('presensi.monitoring');
    }

    public function getpresensi(Request $request)
    {
        $tanggal = $request->tanggal;
        $presensi = DB::table('presensi')
            ->select('presensi.*', 'nama_lengkap')
            ->join('mahasiswa', 'presensi.npm', '=', 'mahasiswa.npm')
            ->where('tgl_presensi', $tanggal)
            ->get();

        return view('presensi.getpresensi', compact('presensi'));
    }

    public function tampilkanpeta(Request $request)
    {
        $id = $request->id;
        $presensi = DB::table('presensi')->where('id', $id)
            ->join('mahasiswa', 'presensi.npm', '=', 'mahasiswa.npm')
            ->first();
        return view('presensi.showmap', compact('presensi'));
    }

    public function laporan()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $mahasiswa = DB::table('mahasiswa')->orderBy('nama_lengkap')->get();
        return view('presensi.laporan', compact('namabulan', 'mahasiswa'));
    }

    public function cetaklaporan(Request $request)
    {
        $npm = $request->npm;
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $mahasiswa = DB::table('mahasiswa')->where('npm', $npm)->first();
        $presensi = DB::table('presensi')
            ->where('npm', $npm)
            ->whereRaw('MONTH(tgl_presensi)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahun . '"')
            ->orderBy('tgl_presensi', 'asc')
            ->get();
        return view('presensi.cetaklaporan', compact('bulan', 'tahun', 'namabulan', 'mahasiswa', 'presensi'));
    }

    public function rekap()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        return view('presensi.rekap', compact('namabulan'));
    }

    public function cetakrekap(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $rekap = DB::table('presensi')
            ->select(
                'presensi.npm',
                'nama_lengkap',
                DB::raw('MAX(IF(DAY(tgl_presensi) = 1, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_1'),
                DB::raw('MAX(IF(DAY(tgl_presensi) = 2, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_2'),
                DB::raw('MAX(IF(DAY(tgl_presensi) = 3, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_3'),
                DB::raw('MAX(IF(DAY(tgl_presensi) = 4, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_4'),
                DB::raw('MAX(IF(DAY(tgl_presensi) = 5, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_5'),
                DB::raw('MAX(IF(DAY(tgl_presensi) = 6, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_6'),
                DB::raw('MAX(IF(DAY(tgl_presensi) = 7, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_7'),
                DB::raw('MAX(IF(DAY(tgl_presensi) = 8, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_8'),
                DB::raw('MAX(IF(DAY(tgl_presensi) = 9, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_9'),
                DB::raw('MAX(IF(DAY(tgl_presensi) = 10, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_10'),
                DB::raw('MAX(IF(DAY(tgl_presensi) = 11, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_11'),
                DB::raw('MAX(IF(DAY(tgl_presensi) = 12, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_12'),
                DB::raw('MAX(IF(DAY(tgl_presensi) = 13, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_13'),
                DB::raw('MAX(IF(DAY(tgl_presensi) = 14, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_14'),
                DB::raw('MAX(IF(DAY(tgl_presensi) = 15, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_15'),
                DB::raw('MAX(IF(DAY(tgl_presensi) = 16, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_16'),
                DB::raw('MAX(IF(DAY(tgl_presensi) = 17, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_17'),
                DB::raw('MAX(IF(DAY(tgl_presensi) = 18, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_18'),
                DB::raw('MAX(IF(DAY(tgl_presensi) = 19, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_19'),
                DB::raw('MAX(IF(DAY(tgl_presensi) = 20, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_20'),
                DB::raw('MAX(IF(DAY(tgl_presensi) = 21, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_21'),
                DB::raw('MAX(IF(DAY(tgl_presensi) = 22, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_22'),
                DB::raw('MAX(IF(DAY(tgl_presensi) = 23, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_23'),
                DB::raw('MAX(IF(DAY(tgl_presensi) = 24, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_24'),
                DB::raw('MAX(IF(DAY(tgl_presensi) = 25, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_25'),
                DB::raw('MAX(IF(DAY(tgl_presensi) = 26, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_26'),
                DB::raw('MAX(IF(DAY(tgl_presensi) = 27, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_27'),
                DB::raw('MAX(IF(DAY(tgl_presensi) = 28, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_28'),
                DB::raw('MAX(IF(DAY(tgl_presensi) = 29, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_29'),
                DB::raw('MAX(IF(DAY(tgl_presensi) = 30, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_30'),
                DB::raw('MAX(IF(DAY(tgl_presensi) = 31, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) as tgl_31'),
            )
            ->join('mahasiswa', 'presensi.npm', '=', 'mahasiswa.npm')
            ->whereRaw('MONTH(tgl_presensi)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahun . '"')
            ->groupByRaw('presensi.npm,nama_lengkap')
            ->get();

        return view('presensi.cetakrekap', compact('bulan', 'tahun', 'namabulan', 'rekap'));
    }
}
