<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TaskController extends Controller 
{
    /**
     * @OA\Get(
     *     path="/api/user",
     *     summary="Get logged-in user details",
     *     @OA\Response(response="200", description="Success"),
     *     security={{"bearerAuth":{}}}
     * )
     */ 
    public function index()
    {
        $tasks = auth()->user()->tasks;
        return response()->json(TaskResource::collection($tasks));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
   
     
        $id=Auth::id();
        Task::create([
        'title' => $request->title,
        'description'=>$request->description,
        'user_id' => $id,
       ]);
     
       $tasks = auth()->user()->tasks;
       return response()->json(
        [ "data" => TaskResource::collection($tasks) ,
       "status" => true  ],200);

    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $this->authorize('show', $task);
        return new TaskResource($task);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);
        $validator = validator($request->all(),[
            'title' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'message' => 'Validation Error']);
        }

        $task->update($request->all());
        return new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();
        return response()->json(['message' => 'Task deleted successfully']);
    }
}
