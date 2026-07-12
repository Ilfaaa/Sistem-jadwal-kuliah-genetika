<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Dosen;


class ManageusersController extends Controller
{
    public function index(Request $request)
    {
        $user_login = $request->session()->get('user_login');
        $countRequest = DB::table('request_kuliah')->count() + DB::table('request_ruang')->count() + DB::table('request_waktu')->count();
        
        $request_keyword = "";
        if($request->keyword){
            if (strtolower($request->keyword) == 'operator' || strtolower($request->keyword) == 'admin' || strtolower($request->keyword) == 'mahasiswa') {
                $role_id =  strtolower($request->keyword) == 'admin' ? 1 : (strtolower($request->keyword) == 'dosen' ? 2 : 3);
                $users = DB::table('users')
                    ->leftJoin('dosen', 'users.kode_dosen', '=', 'dosen.kode_dosen')
                    ->select('users.*', 'dosen.nama as nama_dosen')
                    ->where('role_id', $role_id)->get();
            } else {
            $users = DB::table('users')
                ->leftJoin('dosen', 'users.kode_dosen', '=', 'dosen.kode_dosen')
                ->select('users.*', 'dosen.nama as nama_dosen')
                ->where('users.name', 'LIKE', "%{$request->keyword}%")
                ->orWhere('users.email', 'LIKE', "%{$request->keyword}%")
                ->orWhere('users.username', 'LIKE', "%{$request->keyword}%")->get();
            $request_keyword = $request->keyword;
            }
        } else {
            $users = DB::table('users')
                ->leftJoin('dosen', 'users.kode_dosen', '=', 'dosen.kode_dosen')
                ->select('users.*', 'dosen.nama as nama_dosen')
                ->get();
        }
        return view('manageusers.index', compact('users', 'user_login','request_keyword','countRequest'));
    }
    public function create(Request $request)
    {
        $user_login = $request->session()->get('user_login');
        $countRequest = DB::table('request_kuliah')->count() + DB::table('request_ruang')->count() + DB::table('request_waktu')->count();
        
        return view('manageusers.create', compact('user_login','countRequest'));
    }
    public function store(Request $request)
    {
        $messages = [
            'required' => ':attribute wajib diisi!',
            'min' => ':attribute harus diisi minimal :min karakter!',
            'max' => ':attribute harus diisi maksimal :max karakter!',
            'unique' => ':attribute sudah terdaftar, gunakan :attribute lain!',
            'email' => ':attribute tidak valid!',
            'confirmed' => ':attribute sesuaikan repeat password!',
        ];
        
        $request->validate([
            'nama' => 'required|min:3|max:255',
            'username' => ['required','min:3','max:255','unique:users'],
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|max:255|confirmed'
        ],$messages);
        

        DB::table('users')->insert([
            'name' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'image' => 'default.jpg',
            'role_id' => $request->role_id,
            'is_active' => $request->is_active,
            'password' => Hash::make($request->password),
            'created_at' => date("Y-m-d h:i:s")
        ]);

        return redirect('/manageusers')->with('status', 'Data user Berhasil Ditambahkan!');
    }

    public function edit(Request $request, $id)
    {
        $user_login = $request->session()->get('user_login');
        $countRequest = DB::table('request_kuliah')->count() + DB::table('request_ruang')->count() + DB::table('request_waktu')->count();
        
        $user = DB::table('users')->where('id_user', $id)->first();
        return view('manageusers.edit', compact('user_login', 'user','countRequest'));
    }

    public function update(Request $request, $id)
    {

        // FORM VALIDATION
        $request->validate(
            [
                'nama' => 'required|min:3',
                'email' => 'required|email',
                'password' => 'confirmed'
            ],
            [
                'nama.required'         => 'Nama Lengkap wajib diisi',
                'nama.min'              => 'Nama lengkap minimal 3 karakter',
                'nama.max'              => 'Nama lengkap maksimal 35 karakter',
                'email.required'        => 'Email wajib diisi',
                'email.email'           => 'Email tidak valid',
                'password.confirmed'    => 'Password dan repeat password tidak sesuai'
            ]

        );

        DB::table('users')
        ->where('id_user', $id)
        ->update([
            'name' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'is_active' => $request->is_active,
        ]);

        if($request->password){
            DB::table('users')
            ->where('id_user', $id)
            ->update([
                'password' => Hash::make($request->password)
            ]);
        }
        return redirect('/manageusers')->with('status', 'Data user berhasil diubah');
    }

    public function destroy($id)
    {
        $users = DB::table('users')->get();
        if(count($users) == 1){
            return redirect('/manageusers')->with('status', 'Minimal Tersisa Satu User!');
        };

        DB::table('users')->where('id_user', $id)->delete();
        return redirect('/manageusers')->with('status', 'Data user berhasil dihapus');
    }

    public function approvals(Request $request)
    {
        $user_login = $request->session()->get('user_login');
        $countRequest = DB::table('request_kuliah')->count() + DB::table('request_ruang')->count() + DB::table('request_waktu')->count();
        
        $pendingUsers = DB::table('users')->where('is_active', 2)->get();
        
        return view('manageusers.approvals', compact('pendingUsers', 'user_login', 'countRequest'));
    }

    public function approve($id)
    {
        DB::table('users')->where('id_user', $id)->update(['is_active' => 1]);
        return redirect('/manageusers/approvals')->with('status', 'Akun berhasil disetujui dan diaktifkan.');
    }

    public function reject($id)
    {
        DB::table('users')->where('id_user', $id)->delete();
        return redirect('/manageusers/approvals')->with('status', 'Akun berhasil ditolak dan dihapus.');
    }

    public function assignDosenForm(Request $request, $id)
    {
        $user_login = $request->session()->get('user_login');
        $countRequest = DB::table('request_kuliah')->count() + DB::table('request_ruang')->count() + DB::table('request_waktu')->count();

        $user = DB::table('users')->where('id_user', $id)->first();

        if (!$user || $user->role_id != 2) {
            return redirect('/manageusers')->with('status', 'Hanya akun dengan role Dosen yang bisa dipasangkan!');
        }

        // Ambil dosen yang belum dipasangkan ke akun lain (atau yang sudah dipasangkan ke akun ini)
        $assignedKodeDosen = DB::table('users')
            ->whereNotNull('kode_dosen')
            ->where('id_user', '!=', $id)
            ->pluck('kode_dosen')
            ->toArray();

        $availableDosen = Dosen::whereNotIn('kode_dosen', $assignedKodeDosen)
            ->orderBy('nama', 'asc')
            ->get();

        return view('manageusers.assign-dosen', compact('user_login', 'user', 'availableDosen', 'countRequest'));
    }

    public function assignDosen(Request $request, $id)
    {
        $request->validate([
            'kode_dosen' => 'required|exists:dosen,kode_dosen',
        ], [
            'kode_dosen.required' => 'Pilih dosen yang akan dipasangkan!',
            'kode_dosen.exists' => 'Dosen tidak ditemukan!',
        ]);

        $user = DB::table('users')->where('id_user', $id)->first();

        if (!$user || $user->role_id != 2) {
            return redirect('/manageusers')->with('status', 'Hanya akun dengan role Dosen yang bisa dipasangkan!');
        }

        // Pastikan dosen belum dipasangkan ke akun lain
        $existing = DB::table('users')
            ->where('kode_dosen', $request->kode_dosen)
            ->where('id_user', '!=', $id)
            ->first();

        if ($existing) {
            return redirect("/manageusers/{$id}/assign-dosen")
                ->with('status', 'Dosen ini sudah dipasangkan ke akun lain!');
        }

        DB::table('users')->where('id_user', $id)->update([
            'kode_dosen' => $request->kode_dosen,
        ]);

        return redirect('/manageusers')->with('status', 'Berhasil memasangkan dosen ke akun!');
    }

    public function unassignDosen($id)
    {
        DB::table('users')->where('id_user', $id)->update([
            'kode_dosen' => null,
        ]);

        return redirect('/manageusers')->with('status', 'Berhasil melepas pemetaan dosen dari akun.');
    }
}

