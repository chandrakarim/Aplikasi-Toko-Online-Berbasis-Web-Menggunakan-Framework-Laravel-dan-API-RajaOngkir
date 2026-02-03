<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        /* ===============================
         * 1. AMBIL KERANJANG
         * =============================== */
        $keranjangs = DB::table('keranjang')
            ->join('products', 'products.id', '=', 'keranjang.products_id')
            ->select(
                'products.name as nama_produk',
                'products.price',
                'products.weigth',
                'keranjang.qty'
            )
            ->where('keranjang.user_id', $userId)
            ->get();

        if ($keranjangs->isEmpty()) {
            return back()->with('error','Keranjang kosong');
        }

        /* ===============================
         * 2. HITUNG SUBTOTAL + BERAT
         * =============================== */
        $subtotal = 0;
        $totalBerat = 0;

        foreach ($keranjangs as $item) {
            $subtotal += $item->price * $item->qty;
            $totalBerat += ($item->weigth ?? 1000) * $item->qty;
        }

        /* ===============================
         * 3. ALAMAT USER
         * =============================== */
        $alamat = DB::table('alamat')
            ->join('cities','cities.city_id','=','alamat.cities_id')
            ->join('provinces','provinces.province_id','=','cities.province_id')
            ->select('alamat.*','cities.title as kota','provinces.title as prov')
            ->where('alamat.user_id',$userId)
            ->first();

        if (!$alamat) {
            return redirect()->route('user.alamat')
                ->with('error','Isi alamat dulu');
        }

        /* ===============================
         * 4. AMBIL ONGKIR DARI SESSION
         * =============================== */
        $ongkir = session('checkout_ongkir', 0);
        $courier = session('checkout_courier');
        $service = session('checkout_service');

        if ($ongkir <= 0) {
            return redirect()->route('user.keranjang')
                ->with('error','Silakan pilih kurir terlebih dahulu');
        }

        /* ===============================
         * 5. GRAND TOTAL
         * =============================== */
        $grandTotal = $subtotal + $ongkir;

        /* ===============================
         * 6. VIEW
         * =============================== */
        return view('user.checkout', [
            'invoice'    => 'INV'.date('YmdHis'),
            'keranjangs' => $keranjangs,
            'subtotal'   => $subtotal,
            'ongkir'     => $ongkir,
            'grandTotal' => $grandTotal,
            'courier'    => $courier,
            'service'    => $service,
            'alamat'     => $alamat,
            'totalBerat' => $totalBerat
        ]);
    }
}
