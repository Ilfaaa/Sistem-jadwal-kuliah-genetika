<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlockingJadwalDosen;
use App\Models\Dosen;
use Carbon\Carbon;

class BlockingJadwalController extends Controller
{
    public function index(Request $request)
    {
        $dosen = Dosen::orderBy('nama', 'asc')->get();
        $blocking = BlockingJadwalDosen::with('dosen')->get();
        $user_login = $request->session()->get('user_login');

        return view('manageblocking.index', compact('dosen', 'blocking', 'user_login'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_dosen' => 'required|exists:dosen,kode_dosen',
            'hari' => 'required|string',
            'jam_mulai' => 'required|date_format:H:i',
        ]);

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