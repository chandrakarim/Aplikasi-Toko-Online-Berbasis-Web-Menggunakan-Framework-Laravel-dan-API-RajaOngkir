<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Alamat_toko;
use Illuminate\Support\Facades\DB;
class Alamat_tokoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // HAPUS SEMUA DATA LAMA
        DB::table('alamat_toko')->truncate();
        //
        $data = [
            ['city_id' => '39',
            'postal_code' => '55112',
            'detail' => 'jl.Janti,Kec.Banguntapan,Kab.Bantul,Daerah Istimewah Yogyakarta']
        ];
        Alamat_toko::insert($data);
    }
}
