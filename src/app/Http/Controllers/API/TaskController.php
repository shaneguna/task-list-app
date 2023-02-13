<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use App\Repositories\TaskRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected TaskRepositoryInterface $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function index(): JsonResponse
    {
        $tasks = $this->taskRepository->all();

        return response()->json($tasks);
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        $this->taskRepository->create($request->all());
        $tasks = $this->taskRepository->all();

        return response()->json($tasks, 201);
    }

    public function show($id): JsonResponse
    {
        $task = $this->taskRepository->findById($id);

        return response()->json($task);
    }

    public function update(Request $request): JsonResponse
    {
        foreach ($request->all() as $item) {
            try {
                $record = $this->taskRepository->findById($item['id']);
                $record->sort_order = $item['sort_order'];
                $record->save();
            } catch (\Throwable $exception) {
                return response()->json($exception->getMessage(), 422);
            }
        }

        return response()->json();
    }

    public function delete(Request $request, $id): JsonResponse
    {
        $task = $this->taskRepository->update($id, $request->all());

        return response()->json($task);
    }
}
