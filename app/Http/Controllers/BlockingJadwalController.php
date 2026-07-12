<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlockingJadwalDosen;
use App\Models\Dosen;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BlockingJadwalController extends Controller
{
    public function index(Request $request)
    {
        $user_login = $request->session()->get('user_login');

        // Cek apakah user adalah dosen (role_id == 2)
        if ($user_login->role_id == 2) {
            // Ambil kode_dosen yang terhubung ke akun user ini
            $userData = DB::table('users')->where('id_user', $user_login->id_user)->first();
            $kodeDosen = $userData->kode_dosen ?? null;

            if (!$kodeDosen) {
                // Dosen belum dipasangkan, tampilkan halaman dengan peringatan
                $dosen = collect();
                $blocking = collect();
                $belumDipasangkan = true;
                return view('manageblocking.index', compact('dosen', 'blocking', 'user_login', 'belumDipasangkan'));
            }

            // Hanya tampilkan dosen yang terhubung dengan akun ini
            $dosen = Dosen::where('kode_dosen', $kodeDosen)->get();
            $blocking = BlockingJadwalDosen::with('dosen')->get();
        } else {
            // Admin: tampilkan semua dosen
            $dosen = Dosen::orderBy('nama', 'asc')->get();
            $blocking = BlockingJadwalDosen::with('dosen')->get();
        }

        $belumDipasangkan = false;
        return view('manageblocking.index', compact('dosen', 'blocking', 'user_login', 'belumDipasangkan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_dosen' => 'required|exists:dosen,kode_dosen',
            'hari' => 'required|string',
            'jam_mulai' => 'required|date_format:H:i',
        ]);

        $user_login = $request->session()->get('user_login');

        // Jika user adalah dosen, validasi bahwa kode_dosen sesuai dengan akunnya
        if ($user_login->role_id == 2) {
            $userData = DB::table('users')->where('id_user', $user_login->id_user)->first();
            if (!$userData->kode_dosen || $userData->kode_dosen !== $request->kode_dosen) {
                return response()->json([
                    'error' => 'Anda hanya bisa memblokir jadwal untuk diri sendiri.'
                ], 403);
            }
        }

        $jamMulai = $request->jam_mulai;
        $jamSelesai = Carbon::createFromFormat('H:i', $jamMulai)
            ->addMinutes(10)
            ->format('H:i');

        $existing = BlockingJadwalDosen::where('hari', $request->hari)
            ->where('jam_mulai', $jamMulai)
            ->get();

        foreach ($existing as $e) {
            if ($e->kode_dosen == $request->kode_dosen) {
                return response()->json([
                    'error' => 'Slot ini sudah diblok oleh dosen yang dipilih.'
                ], 422);
            }
        }

        if ($existing->count() >= 2) {
            return response()->json([
                'error' => 'Slot ini sudah penuh. Maksimal 2 dosen dalam 1 slot.'
            ], 422);
        }

        $countDosen = BlockingJadwalDosen::where('kode_dosen', $request->kode_dosen)->count();

        if ($countDosen >= 30) {
            return response()->json([
                'error' => 'Maksimal blocking adalah 5 jam atau 30 slot per dosen.'
            ], 422);
        }

        BlockingJadwalDosen::create([
            'kode_dosen' => $request->kode_dosen,
            'hari' => $request->hari,
            'jam_mulai' => $jamMulai,
            'jam_selesai' => $jamSelesai,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Slot berhasil diblok.',
            'jam_selesai' => $jamSelesai,
        ]);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'kode_dosen' => 'required|exists:dosen,kode_dosen',
            'hari' => 'required|string',
            'jam_mulai' => 'required|date_format:H:i',
        ]);

        $user_login = $request->session()->get('user_login');

        // Jika user adalah dosen, validasi bahwa kode_dosen sesuai dengan akunnya
        if ($user_login->role_id == 2) {
            $userData = DB::table('users')->where('id_user', $user_login->id_user)->first();
            if (!$userData->kode_dosen || $userData->kode_dosen !== $request->kode_dosen) {
                return response()->json([
                    'error' => 'Anda hanya bisa menghapus blok jadwal milik sendiri.'
                ], 403);
            }
        }

        $data = BlockingJadwalDosen::where('kode_dosen', $request->kode_dosen)
            ->where('hari', $request->hari)
            ->where('jam_mulai', $request->jam_mulai)
            ->first();

        if (!$data) {
            return response()->json([
                'error' => 'Tidak bisa menghapus blok milik dosen lain.'
            ], 403);
        }

        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Slot berhasil dihapus.'
        ]);
    }
}