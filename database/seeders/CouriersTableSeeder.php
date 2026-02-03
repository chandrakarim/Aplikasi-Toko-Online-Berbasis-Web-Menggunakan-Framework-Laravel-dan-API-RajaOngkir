<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Courier;

class CouriersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['code' => 'jne', 'title' => 'JNE'],
            ['code' => 'pos', 'title' => 'POS Indonesia'],
            ['code' => 'tiki', 'title' => 'TIKI'],
            ['code' => 'jnt', 'title' => 'J&T'],
            ['code' => 'sicepat', 'title' => 'SiCepat'],
            ['code' => 'anteraja', 'title' => 'AnterAja'],
            ['code' => 'ninja', 'title' => 'Ninja Xpress'],
            ['code' => 'lion', 'title' => 'Lion Parcel'],
            ['code' => 'sap', 'title' => 'SAP Express'],
            ['code' => 'wahana', 'title' => 'Wahana'],
            ['code' => 'rex', 'title' => 'REX'],
            ['code' => 'ide', 'title' => 'ID Express'],
            ['code' => 'sentral', 'title' => 'Sentral Cargo'],
            ['code' => 'first', 'title' => 'First Logistics'],
        ];

        Courier::insert($data);
    }
}
