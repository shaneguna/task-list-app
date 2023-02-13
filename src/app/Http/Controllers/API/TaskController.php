<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use App\Repositories\TaskRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TaskController extends Controller
{
    protected TaskRepositoryInterface $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function index(): JsonResponse
    {
        try {
            $tasks = $this->taskRepository->all();

        } catch (\Throwable $exception) {
            return response()->json($exception->getMessage(), 422);
        }

        return response()->json($tasks);
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        try {
            $this->taskRepository->create($request->all());
            $tasks = $this->taskRepository->all();
        } catch (\Throwable $exception) {
            return response()->json($exception->getMessage(), 422);
        }

        return response()->json($tasks, 201);
    }

    public function update(Request $request): JsonResponse
    {
        foreach ($request->all() as $item) {
            try {
                $record = $this->taskRepository->findById($item['id']);

                $record->sort_order = $item['sort_order'];
                $record->label = $item['label'];

                $record->save();
            } catch (\Throwable $exception) {
                return response()->json($exception->getMessage(), 422);
            }
        }

        return response()->json();
    }

    public function delete($id): JsonResponse
    {
        try {
            $task = $this->taskRepository->delete($id);

        } catch (\Throwable $exception) {
            return response()->json($exception->getMessage(), 422);
        }

        return response()->json($task);
    }

    public function markCompleted($id): JsonResponse
    {
        $data = [
            'completed_at' => Carbon::now()->toIso8601String(),
        ];

        try {
            $this->taskRepository->update($id, $data);

            $tasks = $this->taskRepository->all();
        } catch (\Throwable $exception) {
            return response()->json($exception->getMessage(), 422);
        }

        return response()->json($tasks);
    }
}
