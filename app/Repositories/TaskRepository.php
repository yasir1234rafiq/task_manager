<?php
namespace App\Repositories;

use App\Models\Task;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskRepository implements TaskRepositoryInterface
{
    protected $model;

    public function __construct(Task $model)
    {
        $this->model = $model;
    }

    public function create(array $data): Task
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $task = $this->model->find($id);
        if ($task) {
            return $task->update($data);
        }
        return false;
    }

    public function find(int $id): ?Task
    {
        return $this->model->find($id);
    }

    public function delete(int $id): bool
    {
        $task = $this->model->find($id);
        if ($task) {
            return $task->delete();
        }
        return false;
    }

    public function all(array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->newQuery();
        // Apply filters if any
        if (isset($filters['from_date'])) {
            $query->where('date', '>=', $filters['from_date']);
        }
        if (isset($filters['to_date'])) {
            $query->where('date', '<=', $filters['to_date']);
        }
        if (isset($filters['place'])) {
            $query->where('place', 'like', "%{$filters['place']}%");
        }
        if (isset($filters['organizer_id'])) {
            $query->where('organizer_id', $filters['organizer_id']);
        }
        return $query->paginate(6);
    }
}
