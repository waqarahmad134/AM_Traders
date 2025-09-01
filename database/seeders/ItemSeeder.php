<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class ItemSeeder extends Seeder
{
    public function run()
    {
        $items = [
            ['code' => 'Y01', 'name' => 'Yasee Glucometer 2in1'],
            ['code' => 'Y04', 'name' => 'Yasee Strips Vail Pack'],
            ['code' => 'L01', 'name' => 'Lancet'],
            ['code' => 'DS01', 'name' => 'DS Glucometer'],
            ['code' => 'DS02', 'name' => 'DS Sugar Strips'],
            ['code' => 'Y03', 'name' => 'Yasee Strips Foil Pack'],
            ['code' => 'Y05', 'name' => 'Yasee Strips Uric Acid'],
            ['code' => 'Y02', 'name' => 'Yasee Glucometer'],
            ['code' => 'MN01', 'name' => 'Mesh Nebulizer JSL-W303'],
            ['code' => 'Y06', 'name' => 'Yasee BP Monitor S09'],
        ];

        $now = Carbon::now();

        foreach ($items as $item) {
            DB::table('items')->insert([
                'item_code' => $item['code'],
                'item_name' => $item['name'],
                'status' => "active",
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
