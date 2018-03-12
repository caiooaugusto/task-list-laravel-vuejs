<?php

namespace TaskList\Http\Controllers;

use TaskList\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function create(Request $request)
    {
        $task = new Task;
        $task->fill([
            'title'  => $request->title,
            'status' => $request->status,
            'description' => $request->description,
            'deadline_date' => $request->deadline_date,
            'deadline_date_string' => $request->deadline_date_string
        ]);
        $task->save();
        return response()->json($task);
    }

    public function get(Request $request)
    {
        $tasks = Task::all();
        return response()->json($tasks);
    }

    public function getByRfid(Request $request)
    {
        $user = Task::where('rfid', '=' ,$request->rfid)->firstOrFail();
        return response()->json($user);
    }

    public function update(Request $request)
    {
        $user = Task::find($request->id);
        $user->fill([
            'name' => $request->name,
            'rfid' => $request->rfid,
            'date' => $request->date,
            'placa' => $request->placa,
            'empresa' => $request->empresa,
            'cpf' => $request->cpf,
            'photo' => $request->photo
        ]);
        $user->save();
        return response()->json($user);
    }

    public function delete(Request $request)
    {
        return response()->json(Task::destroy($request->id));
    }

    public function syncUsers(Request $request)
    {
        $users = Task::all();
        return response()->json($users);
    }
}
