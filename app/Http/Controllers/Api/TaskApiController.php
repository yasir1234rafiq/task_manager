<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;

class TaskApiController extends Controller
{
    public function index() {
        return response()->json(Task::with('feedback')->get());
    }

    public function store(Request $request) {
        // Check if the authenticated user has the required role
        if (Auth::user()->role !== 'Manager' && Auth::user()->role !== 'Admin') {
            return response()->json(['error' => 'Unauthorized access. Your role is User.'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string',
        ]);

        $task = Task::create(array_merge($validated, ['user_id' => Auth::id()]));

        return response()->json($task, 201);
    }

    public function update(Request $request, Task $task) {
        // Check if the authenticated user is allowed to update the task
        if (Auth::user()->role === 'User') {
            return response()->json(['error' => 'Unauthorized access. Your role is User.'], 403);
        }

        if (Auth::user()->role === 'Manager' && $task->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized access. You can only edit your own tasks.'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|required|in:To Do,In Progress,Completed',
        ]);

        $task->update($validated);

        return response()->json($task);
    }

    public function destroy(Task $task) {
        // Check if the authenticated user is allowed to delete the task
        if (Auth::user()->role === 'User') {
            return response()->json(['error' => 'Unauthorized access. Your role is User.'], 403);
        }

        if (Auth::user()->role === 'Manager' && $task->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized access. You can only delete your own tasks.'], 403);
        }

        $task->delete();

        return response()->json(null, 204);
    }
    public function submitFeedback(Request $request, Task $task) {
        $validated = $request->validate([
            'comment' => 'required|string|max:255',
        ]);

        $feedback = Feedback::create([
            'task_id' => $task->id,
            'user_id' => Auth::id(),
            'comment' => $validated['comment'],
        ]);

        return response()->json($feedback, 201);
    }
}
