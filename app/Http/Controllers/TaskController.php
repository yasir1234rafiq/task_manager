<?php

namespace App\Http\Controllers;

use App\Events\TaskUpdated;
use Illuminate\Http\Request;
use App\Services\TaskService;
use App\Models\Task;
use App\Models\User;
use App\Models\Feedback;
use App\Mail\TaskUpdatedMail;

use Illuminate\Support\Facades\Mail;


class TaskController extends Controller
{
protected $taskService;

public function __construct(TaskService $taskService)
{
$this->taskService = $taskService;
}

public function index(Request $request)
{
$filters = [
'from_date' => $request->input('from_date'),
'to_date' => $request->input('to_date'),
'place' => $request->input('place'),
];

$user = $request->user();
if ($user->role === 'manager') {
$filters['user_id'] = $user->id;
} elseif ($user->role === 'user') {
return redirect()->route('home')->with('error', 'You are not authorized to access this route.');
}

$data['tasks'] = $this->taskService->getAllTask($filters);

return view('tasks.index', $data);
}

public function create()
{
return view('tasks.create');
}

public function store(Request $request)
{

$request->validate([
    'title' => 'required|string|max:255',
    'description' => 'required|string',
]);

$userId = auth()->user()->id;

$data = $request->only(['title', 'description']);

$data['user_id'] = $userId;
$data['status'] = 'to do';

$this->taskService->createTask($data);

return redirect()->route('tasks.index')->with('success', 'Task has been created successfully.');
}

public function show(Task $task)
{
return view('tasks.index', compact('task'));
}

public function edit(Task $task)
{
return view('tasks.edit', compact('task'));
}

public function update(Request $request, $id)
{


    $request->validate([
'title' => 'required',
'description' => 'required',

]);

    $task = Task::findOrFail($id);
$data = $request->only(['title', 'description', 'status']);

    $task->update($data);
//    $users = User::all();
//    foreach ($users as $user) {
//        Mail::to($user->email)->send(new TaskUpdatedMail($task));
//    }
    event(new TaskUpdated($task));

return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
}

public function destroy(Task $task)
{
$this->taskService->deleteTask($task->id);
return redirect()->route('tasks.index')->with('success', 'Task has been deleted successfully.');
}

public function task(Request $request)
{
$filters = [
'from_date' => $request->input('from_date'),
'to_date' => $request->input('to_date'),
'place' => $request->input('place'),
];

$data['tasks'] = $this->taskService->getAllTask($filters);

return view('tasks', $data);
}
    public function taskshow($id)
    {

        $task = Task::with('feedback.user')->findOrFail($id);

        return view('show', compact('task'));
    }

    // Mark the task as complete
    public function complete($id)
    {
        $task = Task::findOrFail($id);
        $task->status = 'Completed';
        $task->save();

        return redirect()->route('task.show', $id)->with('success', 'Task marked as completed.');
    }

    public function feedback(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string|max:255',
        ]);

       $data= Feedback::create([
            'task_id' => $id,
            'user_id' => auth()->user()->id,
            'comment' => $request->comment,
        ]);

        return response()->json($data, 201);
    }
}
