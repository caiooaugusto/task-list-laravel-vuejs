<?php

namespace TaskList\Http\Controllers;

use TaskList\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function create(Request $request)
    {
        $task = new Task;
        $status = $request->status ? $request->status : false;
        $task->fill([
            'title'  => $request->title,
            'description' => $request->description,
            'deadline_date_string' => $request->deadline_date_string,
            'status' => $status
        ]);
        $task->save();
        return response()->json($task);
    }

    public function get(Request $request)
    {
        $tasks = Task::all();
        return response()->json($tasks);
    }

    public function update(Request $request)
    {
        $task = Task::find($request->id);
        $status = $request->status ? $request->status : false;
        $task->fill([
            'title'  => $request->title,
            'description' => $request->description,
            'deadline_date_string' => $request->deadline_date_string,
            'status' => $status
        ]);
        $task->save();
        return response()->json($task);
    }

    public function delete(Request $request)
    {
        return response()->json(Task::destroy($request->id));
    }
}
