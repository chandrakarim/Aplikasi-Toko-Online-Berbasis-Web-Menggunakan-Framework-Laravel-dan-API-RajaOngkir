<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Alamat_toko;
class Alamat_tokoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data = [
            ['city_id' => '39','detail' => 'jl.Janti,Kec.Banguntapan,Kab.Bantul,Daerah Istimewah Yogyakarta']
        ];
        Alamat_toko::insert($data);
    }
}
