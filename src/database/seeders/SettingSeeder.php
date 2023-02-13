<?php

namespace Database\Seeders;

use App\Models\Setting;

class SettingSeeder extends DatabaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::factory()->create([
            'param' => 'allow_duplicates',
            'value' => 'true',
        ]);
    }
}
