<?php
namespace App\Services;

use App\Repositories\TaskRepositoryInterface;
use App\Models\Task;

class TaskService
{
    protected $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->TaskRepository = $taskRepository;
    }

    public function createTask(array $data): Task
    {
        return $this->TaskRepository->create($data);
    }

    public function updateTask(int $id, array $data): bool
    {
        return $this->TaskRepository->update($id, $data);
    }

    public function getTask(int $id): ?Task
    {
        return $this->TaskRepository->find($id);
    }

    public function deleteTask(int $id): bool
    {
        return $this->TaskRepository->delete($id);
    }

    public function getAllTask(array $filters = [])
    {
        return $this->TaskRepository->all($filters);
    }
}
