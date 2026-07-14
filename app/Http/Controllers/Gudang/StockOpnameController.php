<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\BahanBaku;
use App\Models\StockOpname;
use App\Models\DetailStockOpname;
use App\Models\RiwayatStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockOpnameController extends Controller
{
    public function index()
    {
        $opnames = StockOpname::with('user')->latest()->paginate(15);
        return view('gudang.stock-opname.index', compact('opnames'));
    }

    public function create()
    {
        // Cek jika ada draft yang belum selesai
        $draftAktif = StockOpname::where('status', 'draft')->where('user_id', auth()->id())->first();
        if ($draftAktif) {
            return redirect()->route('gudang.stock-opname.show', $draftAktif)
                ->with('error', 'Anda masih memiliki stock opname yang belum diselesaikan.');
        }

        $bahanBakus = BahanBaku::where('is_active', true)->with(['kategori', 'satuan'])->orderBy('nama_bahan')->get();
        return view('gudang.stock-opname.create', compact('bahanBakus'));
    }

    public function store(Request $request)
    {
        $opname = DB::transaction(function () use ($request) {
            $opname = StockOpname::create([
                'kode_opname'    => StockOpname::generateKode(),
                'user_id'        => auth()->id(),
                'tanggal_opname' => today(),
                'keterangan'     => $request->keterangan,
                'status'         => 'draft',
            ]);

            // Generate detail dari semua bahan baku aktif
            $bahanBakus = BahanBaku::where('is_active', true)->get();
            foreach ($bahanBakus as $bahan) {
                DetailStockOpname::create([
                    'stock_opname_id' => $opname->id,
                    'bahan_baku_id'   => $bahan->id,
                    'stok_sistem'     => $bahan->stok_saat_ini,
                    'stok_fisik'      => null,
                    'selisih'         => null,
                ]);
            }

            return $opname;
        });

        return redirect()->route('gudang.stock-opname.show', $opname)->with('success', 'Stock opname berhasil dibuat. Silakan isi stok fisik.');
    }

    public function show(StockOpname $stockOpname)
    {
        $stockOpname->load('detail.bahanBaku.satuan', 'user');
        return view('gudang.stock-opname.show', compact('stockOpname'));
    }

    public function updateDetail(Request $request, StockOpname $stockOpname)
    {
        if ($stockOpname->status === 'selesai') {
            return back()->with('error', 'Stock opname sudah selesai dan tidak dapat diubah.');
        }

        $request->validate([
            'details'           => 'required|array',
            'details.*.id'      => 'required|exists:detail_stock_opname,id',
            'details.*.stok_fisik' => 'required|numeric|min:0',
        ]);

        foreach ($request->details as $item) {
            $detail = DetailStockOpname::find($item['id']);
            if ($detail && $detail->stock_opname_id === $stockOpname->id) {
                $selisih = $item['stok_fisik'] - $detail->stok_sistem;
                $detail->update([
                    'stok_fisik' => $item['stok_fisik'],
                    'selisih'    => $selisih,
                ]);
            }
        }

        return back()->with('success', 'Data stok fisik berhasil disimpan.');
    }

    public function selesaikan(StockOpname $stockOpname)
    {
        if ($stockOpname->status === 'selesai') {
            return back()->with('error', 'Stock opname sudah selesai.');
        }

        $hasNull = $stockOpname->detail()->whereNull('stok_fisik')->exists();
        if ($hasNull) {
            return back()->with('error', 'Harap isi semua stok fisik sebelum menyelesaikan stock opname.');
        }

        DB::transaction(function () use ($stockOpname) {
            $stockOpname->update(['status' => 'selesai']);

            foreach ($stockOpname->detail as $detail) {
                if ($detail->selisih != 0) {
                    $bahan = $detail->bahanBaku;
                    $stokSebelum = $bahan->stok_saat_ini;
                    $stokSesudah = $detail->stok_fisik;

                    $bahan->update(['stok_saat_ini' => $stokSesudah]);

                    RiwayatStok::catat(
                        $bahan->id,
                        'penyesuaian',
                        $stockOpname->id,
                        'stock_opname',
                        abs($detail->selisih),
                        $stokSebelum,
                        $stokSesudah,
                        'Stock Opname: ' . $stockOpname->kode_opname . ' | Selisih: ' . $detail->selisih
                    );
                }
            }
        });

        return redirect()->route('gudang.stock-opname.index')->with('success', 'Stock opname berhasil diselesaikan dan stok telah disesuaikan.');
    }
}