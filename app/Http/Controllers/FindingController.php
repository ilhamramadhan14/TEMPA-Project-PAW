<?php

namespace App\Http\Controllers;

use App\Models\Finding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FindingController extends Controller
{
    public function index(Request $request)
    {
        $query = Finding::with('user')->where('status', 'published');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_kompetisi', 'like', "%{$search}%")
                  ->orWhere('judul_tim', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori') && $request->kategori !== 'Semua') {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('tahap_ide') && $request->tahap_ide !== 'Semua') {
            $query->where('tahap_ide', $request->tahap_ide);
        }

        if ($request->filled('keterampilan') && $request->keterampilan !== 'Semua') {
            $query->whereJsonContains('keterampilan', ['nama' => $request->keterampilan]);
        }

        $findings = $query->latest()->get();

        return view('findings.index', compact('findings'));
    }

    public function create()
    {
        return view('findings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kompetisi' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'max:100'],
            'judul_tim' => ['required', 'string', 'max:255'],
            'tahap_ide' => ['required', 'string', 'max:100'],
            'anggota_dibutuhkan' => ['required', 'integer', 'min:1', 'max:10'],
            'tenggat_pengajuan' => ['required', 'date', 'after_or_equal:today'],
            'keterampilan' => ['required', 'array', 'min:1'],
            'deskripsi' => ['required', 'string'],
            'action_type' => ['required', 'in:published,draft'],
        ]);

        $formattedSkills = collect($validated['keterampilan'])->map(function ($skill) {
            return [
                'nama' => $skill,
                'status' => 'Dibutuhkan',
            ];
        })->values()->all();

        Finding::create([
            'user_id' => Auth::id(),
            'nama_kompetisi' => $validated['nama_kompetisi'],
            'kategori' => $validated['kategori'],
            'judul_tim' => $validated['judul_tim'],
            'tahap_ide' => $validated['tahap_ide'],
            'anggota_dibutuhkan' => $validated['anggota_dibutuhkan'],
            'anggota_terkumpul' => 1,
            'tenggat_pengajuan' => $validated['tenggat_pengajuan'],
            'keterampilan' => $formattedSkills,
            'deskripsi' => $validated['deskripsi'],
            'status' => $validated['action_type'],
        ]);

        $pesan = $validated['action_type'] === 'published'
            ? 'Lowongan Finding Partner berhasil dipublikasikan!'
            : 'Lowongan berhasil disimpan sebagai draft.';

        return redirect()->route('findings.index')->with('success', $pesan);
    }
}