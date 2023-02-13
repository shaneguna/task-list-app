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
            'id' => 1,
            'label' => 'Task 1',
            'sort_order' => 1,
        ]);

        Task::factory()->create([
            'id' => 2,
            'label' => 'Task 2',
            'sort_order' => 2,
        ]);

        Task::factory()->create([
            'id' => 3,
            'label' => 'Task 3',
            'sort_order' => 3,
        ]);
    }
}
