<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user_login = $request->session()->get('user_login');
        $countRequest = DB::table('request_kuliah')->count() + DB::table('request_ruang')->count() + DB::table('request_waktu')->count();
        
        return view('manageprofile.index', compact('user_login','countRequest'));
    }

    public function editprofile(Request $request)
    {
        $user_login = $request->session()->get('user_login');
        $countRequest = DB::table('request_kuliah')->count() + DB::table('request_ruang')->count() + DB::table('request_waktu')->count();
        
        return view('manageprofile.editprofile', compact('user_login','countRequest'));
    }

    public function updateprofile(Request $request)
    {
        $user_login = $request->session()->get('user_login');


        $request->validate(
            [
                'name' => 'required|min:3',
            ],
            [
                'nama.required' => 'Kolom nama harap di isi.',
                'nama.min' => 'Nama minimal 3 huruf.',
            ]

        );


        $file = $request->file('file');

        if ($file) {
            $imageExtensions = ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'svg', 'svgz', 'cgm', 'djv', 'djvu', 'ico', 'ief','jpe', 'pbm', 'pgm', 'pnm', 'ppm', 'ras', 'rgb', 'tif', 'tiff', 'wbmp', 'xbm', 'xpm', 'xwd'];
            if (in_array(strtolower($file->getClientOriginalExtension()), $imageExtensions)) {
                $renameFileName = rand().$file->getClientOriginalName();
                $file->move('img/profile/',$renameFileName);

                DB::table('users')
                ->where('id_user', $user_login->id_user)
                ->update([
                    'image' => $renameFileName
                ]);

            } else {
                return redirect('editprofile')->with('message','Hanya menerima file berformat gambar');   
            }
        }

        DB::table('users')
        ->where('id_user', $user_login->id_user)
        ->update([
            'name' => $request->name,
        ]);

        $request->session()->flush();

        $data = User::where('email',$request->email)->first();
        session(['user_login' => $data]);


        return redirect('/myprofile')->with('status', 'Berhasil mengubah data user');

        
    }
    public function editpassword(Request $request)
    {
        $user_login = $request->session()->get('user_login');
        $countRequest = DB::table('request_kuliah')->count() + DB::table('request_ruang')->count() + DB::table('request_waktu')->count();
        
        return view('manageprofile.editpassword', compact('user_login','countRequest'));
    }

    public function updatepassword(Request $request)
    {
        $request->validate(
            [
                'current_password' => 'required|min:8',
                'new_password' => 'required|min:8|confirmed',
                'new_password_confirmation' => 'required|min:8'
            ],
            [
                'current_password.required' => 'Password sebelumnya wajib diisi',
                'current_password.min'      => 'Password minimal 8 karakter',
                'new_password.required'     => 'Password baru wajib diisi',
                'new_password.min'          => 'Password minimal 8 karakter',
                'new_password.confirmed'    => 'Password tidak sesuai dengan password konfirmasi',
                'new_password_confirmation.required'  => 'Konfirmasi password baru wajib diisi',
                'new_password_confirmation.min'       => 'Password minimal 8 karakter',
            ]

        );

        $user_login = $request->session()->get('user_login');
        $current_password = $request->current_password;
        $new_password = $request->new_password;

        if (!password_verify($current_password, $user_login->password)) {
            return redirect('/editpassword')->with('status', '<div class="alert alert-danger alert-dismissible fade show" role="alert">Password saat ini salah!<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                </div>');
        } else {
            if ($current_password == $new_password) {
                return redirect('/editpassword')->with('status', '<div class="alert alert-danger alert-dismissible fade show" role="alert">Password baru tidak boleh sama dengan password saat ini!<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                    </div>');
            } else {
                $password_hash = Hash::make($request->new_password);

                $query = DB::table('users')
                ->where('id_user', $user_login->id_user)
                ->update([
                    'password' => $password_hash
                ]);

                if($query) {

                    $request->session()->flush();

                    $data = User::where('email',$user_login->email)->first();
                    session(['user_login' => $data]);

                    return redirect('/editpassword')->with('status', '<div class="alert alert-success alert-dismissible fade show" role="alert">Password berhasil diubah!<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                        </div>');
                } else {
                    return redirect('/editpassword')->with('status', '<div class="alert alert-danger alert-dismissible fade show" role="alert">Password gagal diubah!<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                        </div>');
                }
            }
        }

    }
}
