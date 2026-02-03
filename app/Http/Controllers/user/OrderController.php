<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Detailorder;
use App\Models\Rekening;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    public function index()
    {
        //menampilkan semua data pesanan
        $user_id = \Auth::user()->id;

        $order = DB::table('order')
            ->join('status_order', 'status_order.id', '=', 'order.status_order_id')
            ->select('order.*', 'status_order.name')
            ->where('order.status_order_id', 1)
            ->where('order.user_id', $user_id)->get();
        $dicek = DB::table('order')
            ->join('status_order', 'status_order.id', '=', 'order.status_order_id')
            ->select('order.*', 'status_order.name')
            ->where('order.status_order_id', '!=', 1)
            ->Where('order.status_order_id', '!=', 5)
            ->Where('order.status_order_id', '!=', 6)
            ->where('order.user_id', $user_id)->get();
        $histori = DB::table('order')
            ->join('status_order', 'status_order.id', '=', 'order.status_order_id')
            ->select('order.*', 'status_order.name')
            ->where('order.status_order_id', '!=', 1)
            ->Where('order.status_order_id', '!=', 2)
            ->Where('order.status_order_id', '!=', 3)
            ->Where('order.status_order_id', '!=', 4)
            ->where('order.user_id', $user_id)->get();
        $data = array(
            'order' => $order,
            'dicek' => $dicek,
            'histori' => $histori
        );
        return view('user.order.order', $data);
    }

    public function detail($id)
    {
        //function menampilkan detail order
        $detail_order = DB::table('detail_order')
            ->join('products', 'products.id', '=', 'detail_order.product_id')
            ->join('order', 'order.id', '=', 'detail_order.order_id')
            ->select('products.name as nama_produk', 'products.image', 'detail_order.*', 'products.price', 'order.*')
            ->where('detail_order.order_id', $id)
            ->get();
        $order = DB::table('order')
            ->join('users', 'users.id', '=', 'order.user_id')
            ->join('status_order', 'status_order.id', '=', 'order.status_order_id')
            ->select('order.*', 'users.name as nama_pelanggan', 'status_order.name as status')
            ->where('order.id', $id)
            ->first();
        $data = array(
            'detail' => $detail_order,
            'order'  => $order
        );
        return view('user.order.detail', $data);
    }

    public function sukses()
    {
        //menampilkan view terimakasih jika order berhasil dibuat
        return view('user.terimakasih');
    }

    public function kirimbukti($id, Request $request)
    {
        //mengupload bukti pembayaran
        $order = Order::findOrFail($id);
        if ($request->file('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran')->store('buktibayar', 'public');

            $order->bukti_pembayaran = $file;
            $order->status_order_id  = 2;

            $order->save();
        }
        return redirect()->route('user.order');
    }

    public function pembayaran($id)
    {
        //menampilkan view pembayaran
        $data = array(
            'rekening' => Rekening::all(),
            'order' => Order::findOrFail($id)
        );
        return view('user.order.pembayaran', $data);
    }

    public function pesananditerima($id)
    {
        //function untuk menerima pesanan
        $order = Order::findOrFail($id);
        $order->status_order_id = 5;
        $order->save();

        return redirect()->route('user.order');
    }

    public function pesanandibatalkan($id)
    {
        DB::transaction(function () use ($id) {

            $order = Order::findOrFail($id);

            // hanya kembalikan stok jika sebelumnya sudah mengurangi stok
            if (in_array($order->status_order_id, [3, 4])) {

                $detailOrder = DB::table('detail_order')
                    ->where('order_id', $id)
                    ->get();

                foreach ($detailOrder as $item) {
                    DB::table('products')
                        ->where('id', $item->product_id)
                        ->increment('stok', $item->qty);
                }
            }

            // update status dibatalkan
            $order->status_order_id = 6;
            $order->save();
        });

        return redirect()
            ->route('user.order')
            ->with('status', 'Pesanan berhasil dibatalkan dan stok dikembalikan');
    }


    public function simpan(Request $request)
    {
        DB::beginTransaction();

        try {

            $userid = auth()->id();

            $isCod = $request->metode_pembayaran === 'cod';

            $order = Order::create([
                'invoice' => $request->invoice,
                'user_id' => $userid,
                'subtotal' => $request->subtotal,
                'status_order_id' => $isCod ? 3 : 1,
                'metode_pembayaran' => $request->metode_pembayaran,
                'ongkir' => $request->ongkir,
                'biaya_cod' => $isCod ? 10000 : 0,
                'no_hp' => $request->no_hp,
                'pesan' => $request->pesan
            ]);

            $barang = DB::table('keranjang')
                ->where('user_id', $userid)
                ->get();

            foreach ($barang as $brg) {

                Detailorder::create([
                    'order_id' => $order->id,
                    'product_id' => $brg->products_id,
                    'qty' => $brg->qty,
                ]);

                // 🔥 KURANGI STOK LANGSUNG JIKA COD
                if ($isCod) {
                    DB::table('products')
                        ->where('id', $brg->products_id)
                        ->decrement('stok', $brg->qty);
                }
            }

            DB::table('keranjang')
                ->where('user_id', $userid)
                ->delete();

            DB::commit();

            return redirect()->route('user.order.sukses');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}
