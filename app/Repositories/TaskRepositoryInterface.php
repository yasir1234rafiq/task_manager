<?php

namespace App\Repositories;

use App\Models\Task;

interface TaskRepositoryInterface
{

    public function update(int $id, array $data): bool;
    public function find(int $id): ?Task;
    public function delete(int $id): bool;
    public function all(array $filters = []);
}
