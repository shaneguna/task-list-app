<?php

namespace App\Repositories;

use App\Models\Task;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * To simplify the implementation, since we only have one db instance to connect to, directly use Laravel's model eloquent methods.
 * An interface has been added to implicate that this class is ready for extension if another db connector is required for the model.
 */
class TaskRepository implements TaskRepositoryInterface
{
    public function all(): Collection
    {
        return Task::groupBy('id')
            ->orderBy(\DB::raw('CASE WHEN completed_at IS NULL THEN 1 ELSE 0 END DESC, sort_order'))
            ->get();
    }

    public function findById($id): Task
    {
        return Task::findOrFail($id);
    }

    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function update(int $id, array $data): Task
    {
        $task = $this->findById($id);
        $task->update($data);

        return $task;
    }

    public function delete(int $id): Task
    {
        $task = $this->findById($id);
        $task->delete();

        return $task;
    }
}
