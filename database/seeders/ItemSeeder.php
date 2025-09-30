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
            ['name' => 'Yasee Glucometer 2in1'],
            ['name' => 'Yasee Strips Vail Pack'],
            ['name' => 'Lancet'],
            ['name' => 'DS Glucometer'],
            ['name' => 'DS Sugar Strips'],
            ['name' => 'Yasee Strips Foil Pack'],
            ['name' => 'Yasee Strips Uric Acid'],
            ['name' => 'Yasee Glucometer'],
            ['name' => 'Mesh Nebulizer JSL-W303'],
            ['name' => 'Yasee BP Monitor S09'],
        ];

        $now = Carbon::now();

        foreach ($items as $item) {
            DB::table('items')->insert([
                'item_name' => $item['name'],
                'status' => "active",
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
