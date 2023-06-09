<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Services\Models\Task\RegisterTaskService;
use App\Services\Models\Task\UpdateTaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();
        return response()->json([
            'status' => 'success',
            'tasks' => $tasks,
        ]);
    }

    public function task($id)
    {
        $task = Task::find($id);
        return response()->json([
            'status' => 'success',
            'task' => $task,
        ]);
    }

    public function store(Request $request)
    {
        $data = RegisterTaskRequest::validate($request);
        $service = new RegisterTaskService($data);

        if (!$task = $service->run()) return response(null, 503);
        return response()->json($task, 201);
    }

    public function update(Request $request)
    {
        $data = UpdateTaskRequest::validate($request);
        $service = new UpdateTaskService($data);
        if (!$product = $service->run()) return response(null, 503);
        return response()->json($product, 201);
    }

    public function delete($id)
    {
        $task = Task::find($id);
        if ($task) {
            if($task->delete()) return response(true, 201);
        }
        return response(null, 503);
    }
}
