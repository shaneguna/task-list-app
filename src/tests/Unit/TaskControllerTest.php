<?php

namespace Tests\Unit\Http\Controllers\API;

use App\Http\Controllers\API\TaskController;
use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use App\Repositories\TaskRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    public function testStore(): void
    {
        $request = $this->createMock(StoreTaskRequest::class);
        $request->expects($this->once())
            ->method('all')
            ->willReturn([
                'name' => 'Test Task',
                'description' => 'Test Description',
            ]);

        $taskRepository = $this->createMock(TaskRepository::class);
        $taskRepository->expects($this->once())
            ->method('create')
            ->with([
                'name' => 'Test Task',
                'description' => 'Test Description',
            ]);
        $taskRepository->expects($this->once())
            ->method('all')
            ->willReturn(Task::factory(5)->create());

        $controller = new TaskController($taskRepository);
        $response = $controller->store($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testUpdate(): void
    {
        $task = Task::factory()->create();
        $data = [
            ['id' => $task->id, 'sort_order' => 2],
        ];

        $request = $this->createMock(Request::class);
        $request->expects($this->once())
            ->method('all')
            ->willReturn($data);

        $taskRepository = $this->createMock(TaskRepository::class);
        $taskRepository->expects($this->once())
            ->method('findById')
            ->with($task->id)
            ->willReturn($task);

        $controller = new TaskController($taskRepository);
        $response = $controller->update($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEmpty($response->getData());

        $updatedTask = Task::find($task->id);
        $this->assertEquals(2, $updatedTask->sort_order);
    }

    public function testUpdateWithException(): void
    {
        $task = Task::factory()->create();
        $data = [
            ['id' => $task->id, 'sort_order' => 2],
        ];

        $request = $this->createMock(Request::class);
        $request->expects($this->once())
            ->method('all')
            ->willReturn($data);

        $exceptionMessage = 'Exception message';

        $taskRepository = $this->createMock(TaskRepository::class);
        $taskRepository->expects($this->once())
            ->method('findById')
            ->with($task->id)
            ->willThrowException(new \Exception($exceptionMessage));

        $controller = new TaskController($taskRepository);
        $response = $controller->update($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals($exceptionMessage, $response->getData());
    }
}
