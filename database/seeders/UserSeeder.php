<?php
namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::firstOrCreate(
            ['email' => 'admin@TokoXYZSport.com'],
            [
                'name'     => 'admin',
                'jk'       => 'Laki-laki',
                'no_tlp'   => '0983534727262',
                'password' => Hash::make('rahasia'),
                'role'     => 'admin',
            ]
        );
    }
}
