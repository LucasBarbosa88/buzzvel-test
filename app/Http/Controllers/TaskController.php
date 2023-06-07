<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterTaskRequest;
use App\Models\Task;
use App\Services\Models\Task\RegisterTaskService;
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

        if( !$task = $service->run() ) return response( null, 503 );
        return response()->json( $task , 201 );
    }
}
