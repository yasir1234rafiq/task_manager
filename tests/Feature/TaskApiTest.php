<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Task;
use Laravel\Sanctum\Sanctum;

use Tests\TestCase;

class TaskApiTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_user_can_create_task()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/tasks', [
            'title' => 'New Task',
            'description' => 'Task Description',
            'status' => 'To Do',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('tasks', ['title' => 'New Task']);
    }

    public function test_user_can_update_task()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->putJson('/api/tasks/' . $task->id, [
            'title' => 'Updated Task',
            'description' => 'Updated Description',
            'status' => 'In Progress',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('tasks', ['title' => 'Updated Task']);
    }

    public function test_user_can_delete_task()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->deleteJson('/api/tasks/' . $task->id);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
