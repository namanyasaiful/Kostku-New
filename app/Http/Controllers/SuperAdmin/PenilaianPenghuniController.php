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
                });
            });

        $semuaRecord     = (clone $query)->get();
        $menungguRecord  = (clone $query)->where('status', 'Menunggu')->get();
        $disetujuiRecord = (clone $query)->where('status', 'Disetujui')->get();
        $ditolakRecord   = (clone $query)->where('status', 'Ditolak')->get();

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