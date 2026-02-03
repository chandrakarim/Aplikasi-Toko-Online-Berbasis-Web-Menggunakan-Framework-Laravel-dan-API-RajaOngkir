<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\Province;
use App\Models\City;

class LocationsTableSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Mulai ambil data lokasi dari API...');

        $provRes = Http::withHeaders([
            'accept' => 'application/json',
            'key'    => config('services.komerce.key'),
        ])->get(config('services.komerce.base_url') . '/destination/province');

        // ❌ Jika gagal API atau data kosong, fallback ke JSON
        if (!$provRes->successful() || empty($provRes->json('data'))) {
            $this->command->warn('Gagal ambil provinsi dari API, fallback ke JSON backup...');
            $this->seedFromJson();
            return;
        }

        $provinceBackup = [];
        $cityBackup = [];

        foreach ($provRes->json('data') as $prov) {
            $province = Province::updateOrCreate(
                ['province_id' => $prov['id']],
                ['title' => $prov['name']]
            );

            $provinceBackup[] = [
                'province_id' => $province->province_id,
                'title'       => $province->title,
                'created_at'  => now(),
                'updated_at'  => now(),
            ];

            $this->command->info("Provinsi: {$prov['name']}");

            $cityRes = Http::withHeaders([
                'accept' => 'application/json',
                'key'    => config('services.komerce.key'),
            ])->get(config('services.komerce.base_url') . "/destination/city/{$prov['id']}");

            if (!$cityRes->successful() || empty($cityRes->json('data'))) {
                $this->command->warn("Gagal ambil kota untuk provinsi {$prov['name']}, lanjut...");
                continue;
            }

            foreach ($cityRes->json('data') as $city) {
                $c = City::updateOrCreate(
                    ['city_id' => $city['id']],
                    [
                        'province_id' => $province->province_id,
                        'title'       => $city['name'],
                        'zip_code'    => $city['zip_code'] ?? null,
                    ]
                );

                $cityBackup[] = [
                    'city_id'     => $c->city_id,
                    'province_id' => $c->province_id,
                    'title'       => $c->title,
                    'zip_code'    => $c->zip_code,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ];

                $this->command->info("  Kota: {$city['name']}");
            }
        }

        // 🌟 Simpan backup JSON terbaru
        Storage::put('locations_province_backup.json', json_encode($provinceBackup, JSON_PRETTY_PRINT));
        Storage::put('locations_cities_backup.json', json_encode($cityBackup, JSON_PRETTY_PRINT));

        $this->command->info('Seeder lokasi selesai ✅ (backup JSON terbaru dibuat)');
    }

    private function seedFromJson()
    {
        $provinceFile = storage_path('app/locations_province_backup.json');
        $cityFile     = storage_path('app/locations_cities_backup.json');

        if (!file_exists($provinceFile) || !file_exists($cityFile)) {
            $this->command->error('File JSON backup tidak ditemukan!');
            return;
        }

        // 🌍 Provinsi
        $provinceData = json_decode(file_get_contents($provinceFile), true);
        foreach ($provinceData as $prov) {
            Province::updateOrCreate(
                ['province_id' => $prov['province_id']],
                [
                    'title'      => $prov['title'],
                    'created_at' => $prov['created_at'] ?? now(),
                    'updated_at' => $prov['updated_at'] ?? now(),
                ]
            );
            $this->command->info("Provinsi (JSON): {$prov['title']}");
        }

        // 🏙 Kota
        $cityData = json_decode(file_get_contents($cityFile), true);
        foreach ($cityData as $city) {
            City::updateOrCreate(
                ['city_id' => $city['city_id']],
                [
                    'province_id' => $city['province_id'],
                    'title'       => $city['title'],
                    'zip_code'    => $city['zip_code'] ?? null,
                    'created_at'  => $city['created_at'] ?? now(),
                    'updated_at'  => $city['updated_at'] ?? now(),
                ]
            );
            $this->command->info("Kota (JSON): {$city['title']}");
        }

        $this->command->info('Seeder lokasi dari JSON selesai ✅');
    }
}
