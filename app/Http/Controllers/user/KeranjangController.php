<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Keranjang;
use App\Models\Courier;
use App\Models\Alamat;
use App\Models\Alamat_toko;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class KeranjangController extends Controller
{

    public function index()
    {
        $id_user = auth()->id();

        $keranjangs = DB::table('keranjang')
            ->join('products', 'products.id', '=', 'keranjang.products_id')
            ->select(
                'keranjang.*',
                'products.name as nama_produk',
                'products.image',
                'products.price',
                'products.stok',
                'products.weight'
            )
            ->where('keranjang.user_id', $id_user)
            ->get();


        $cekalamat = Alamat::where('user_id', $id_user)->count();

        $couriers = Courier::orderBy('title')->get();


        /* ================= HITUNG ================= */

        $subtotal = 0;
        $totalBerat = 0;

        foreach ($keranjangs as $k) {
            $subtotal += $k->price * $k->qty;

            // berat minimal 1kg (1000g) kalau kosong
            $totalBerat += max($k->weight, 1000) * $k->qty;
        }

        // RajaOngkir minimal 1 gram
        if ($totalBerat < 1) {
            $totalBerat = 1000;
        }


        return view('user.keranjang', compact(
            'keranjangs',
            'cekalamat',
            'couriers',
            'subtotal',
            'totalBerat'
        ));
    }


    /* ======================================================
       CEK ONGKIR
    ====================================================== */

    public function cekOngkir(Request $r)
    {
        $r->validate([
            'courier' => 'required|string',
            'weight'  => 'required|numeric|min:1'
        ]);

        try {

            $alamat = Alamat::where('user_id', auth()->id())->first();
            $alamatToko = Alamat_toko::first();

            if (!$alamat || !$alamatToko) {
                return response()->json([
                    'data' => [],
                    'message' => 'Alamat belum lengkap'
                ], 422);
            }

            $response = Http::asForm()
                ->withHeaders([
                    'accept' => 'application/json',
                    'key' => env('KOMERCE_API_KEY')
                ])
                ->post(
                    env('KOMERCE_BASE_URL') . '/calculate/domestic-cost',
                    [
                        'origin'      => $alamatToko->city_id,
                        'destination' => $alamat->cities_id,
                        'weight'      => (int)$r->weight,
                        'courier'     => $r->courier
                    ]
                );

            /* DEBUG — aktifkan jika masih error */
            // dd($response->status(), $response->json());

            if (!$response->successful()) {
                return response()->json([
                    'data' => [],
                    'message' => 'API ongkir gagal'
                ], 500);
            }

            return response()->json($response->json());
        } catch (\Throwable $e) {

            return response()->json([
                'data' => [],
                'message' => $e->getMessage()
            ], 500);
        }
    }


    /* ======================================================
       UPDATE QTY
    ====================================================== */

    public function update(Request $request)
    {
        foreach ($request->id as $i => $id) {

            Keranjang::where('id', $id)
                ->where('user_id', auth()->id())
                ->update([
                    'qty' => max(1, (int)$request->qty[$i])
                ]);
        }

        return back()->with('success', 'Keranjang diperbarui');
    }

    public function delete($id)
    {
        Keranjang::where('id', $id)
            ->where('user_id', auth()->id())
            ->delete();

        return back()->with('success', 'Item dihapus');
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'products_id' => 'required|exists:products,id',
            'qty' => 'required|numeric|min:1'
        ]);

        Keranjang::create([
            'user_id' => auth()->id(),
            'products_id' => $request->products_id,
            'qty' => $request->qty
        ]);

        return redirect()
            ->route('user.keranjang')
            ->with('success', 'Produk masuk keranjang');
    }

    public function setOngkirSession(Request $r)
    {
        session([
            'checkout_ongkir' => $r->ongkir,
            'checkout_courier' => $r->courier,
            'checkout_service' => $r->service
        ]);

        return response()->json(['ok' => true]);
    }
}
