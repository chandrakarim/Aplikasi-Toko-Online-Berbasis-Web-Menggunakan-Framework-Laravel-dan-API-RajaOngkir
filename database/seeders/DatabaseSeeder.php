<?php

use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\CouriersTableSeeder;
use Database\Seeders\LocationsTableSeeder;
use Database\Seeders\OrderStatusSeeder;
use Database\Seeders\RekeningTableSeeder;
use Database\Seeders\Alamat_tokoSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CouriersTableSeeder::class,
            LocationsTableSeeder::class,
            OrderStatusSeeder::class,
            RekeningTableSeeder::class,
            Alamat_tokoSeeder::class,
        ]);
    }
}
