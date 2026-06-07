<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Record;
use Illuminate\Http\Request;

class PenilaianPenghuniController extends Controller
{
    public function viewPenilaianPenghuni(Request $request)
    {
        $search = $request->input('search');

        $query = Record::with(['user', 'kamar.kost.user'])
            ->when($search, function ($q) use ($search) {
                $q->whereHas('user', function ($q2) use ($search) {
                    $q2->where('nama', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
                })
                ->orWhereHas('kamar.kost', function ($q2) use ($search) {
                    $q2->where('nama_kost', 'like', "%$search%")
                    ->orWhere('kode_kost', 'like', "%$search%");
                });
            });

        $semuaRecord     = (clone $query)->paginate(10, ['*'], 'semua_page');
        $menungguRecord  = (clone $query)->where('status', 'Menunggu')->paginate(10, ['*'], 'menunggu_page');
        $disetujuiRecord = (clone $query)->where('status', 'Disetujui')->paginate(10, ['*'], 'disetujui_page');
        $ditolakRecord   = (clone $query)->where('status', 'Ditolak')->paginate(10, ['*'], 'ditolak_page');

        return view('pages.superadmin.penilaian-penghuni', compact(
            'semuaRecord',
            'menungguRecord',
            'disetujuiRecord',
            'ditolakRecord'
        ));
    }

    public function setujuiRecord(Record $record)
    {
        $record->update(['status' => 'Disetujui']);

        return redirect()
            ->route('penilaian-penghuni.superadmin')
            ->with('success', 'Penilaian penghuni berhasil disetujui.');
    }

    public function tolakRecord(Record $record)
    {
        $record->delete();

        return redirect()
            ->route('penilaian-penghuni.superadmin')
            ->with('success', 'Penilaian penghuni berhasil ditolak.');
    }

    public function viewRiwayatPenghuni($userId)
    {
        $penghuni = \App\Models\User::findOrFail($userId);

        $records = Record::with(['kamar.kost'])
            ->where('user_id', $userId)
            ->where('status', 'Disetujui')
            ->orderBy('tanggal_keluar', 'desc')
            ->get();

        return view('pages.superadmin.riwayat-penilaian-penghuni-superadmin', compact('penghuni', 'records'));
    }
}