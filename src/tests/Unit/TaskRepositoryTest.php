<?php

use App\Models\Task;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use App\Repositories\TaskRepository;
use Database\Seeders\TaskSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class TaskRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /** @var TaskRepository */
    private TaskRepositoryInterface $taskRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->taskRepository = new TaskRepository();
    }

    public function testAllMethodReturnsACollection()
    {
        $this->seed(TaskSeeder::class);
        $tasks = $this->taskRepository->all();

        $this->assertInstanceOf(Collection::class, $tasks);
    }

    public function testFindByIdMethodReturnsATask()
    {
        $this->seed(TaskSeeder::class);
        $task = $this->taskRepository->findById(1);

        $this->assertInstanceOf(Task::class, $task);
    }

    public function testFindByIdMethodThrowsExceptionForInvalidId()
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $this->taskRepository->findById(999);
    }

    public function testCreateMethodCreatesAndReturnsATask()
    {
        $data = [
            'label' => 'Test Task',
            'sort_order' => 1,
            'completed_at' => null,
        ];
        $task = $this->taskRepository->create($data);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertDatabaseHas('tasks', $data);
    }

    public function testUpdateMethodUpdatesAndReturnsATask()
    {
        $this->seed(TaskSeeder::class);
        $data = [
            'label' => 'Updated Task',
            'sort_order' => 2,
        ];
        $task = $this->taskRepository->update(1, $data);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertDatabaseHas('tasks', $data);
    }

    public function testDeleteMethodDeletesAndReturnsATask()
    {
        $this->seed(TaskSeeder::class);
        $task = $this->taskRepository->delete(1);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertDatabaseMissing('tasks', [
            'id' => 1,
        ]);
    }
}
