<?php

namespace Database\Seeders;

use App\Models\Task;

class TaskSeeder extends DatabaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Task::factory()->create([
            'id' => 1
        ]);
    }
}
