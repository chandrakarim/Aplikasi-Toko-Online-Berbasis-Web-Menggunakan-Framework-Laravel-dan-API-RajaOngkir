<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Rekening;
class RekeningTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['bank_name' => 'BRI','atas_nama'=>'Toko XYZ Sport','no_rekening'=>'63737364845']
        ];
        Rekening::insert($data);
    }
}
