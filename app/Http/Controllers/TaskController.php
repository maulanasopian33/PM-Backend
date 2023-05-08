<?php

namespace App\Http\Controllers;

use App\Models\task;
use App\Models\todo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use PhpParser\Node\Stmt\Return_;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $task = task::where('assigment','LIKE',"%{$user->id}%")->get();
        return response()->json([
            'status' => true,
            'count'  => $task->count(),
            'data'   => $task
        ]);
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
    public function store(Request $req)
    {
        // $file = $req->avatar->move(public_path('uploads/image'), $req->avatar->getClientOriginalName());
        // $image_path = '/uploads/image/'.$req->avatar->getClientOriginalName();
        try{
            $save = task::create([
                'id_task'   => Str::uuid()->toString(),
                'name'      => $req->name,
                'assigment' => $req->assigment,
                'start_date'=> $req->start_date,
                'due_date'  => $req->due_date,
                'deskripsi' => $req->deskripsi,
                'priority'  => $req->priority,
                'status'    => $req->status,
                'avatar'    => '$image_path',
                'workspace' => $req->workspace
            ]);
            return response()->json([
                'status'  => true,
                'message' => 'created',
                'data'    => $save
            ],200);
        }catch(Exception $e){
            return response()->json([
                'status'  => false,
                'message' => $e
            ],400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try{
            $data =task::where('id_task',$id)->get();
            $data[0]->status = $request->status;
            $data[0]->save();
            return response()->json([
                'status'  => true,
                'message' => 'Updated',
            ],200);
        }catch(\Exception $e){
            return response()->json([
                'status'  => false,
                'message' => "Teing error Kunaon?",
            ],200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function getbyworkspace($id){
        $user = Auth::user();
        $task = task::where('assigment','LIKE',"%{$user->id}%")->where('workspace',$id)->get();
        return response()->json([
            'status' => true,
            'data'   => $task,
            'count'  => $task->count()
        ]);
    }
    public function getbyassigment(){
        $user = Auth::user();
        return task::where('assigment','LIKE',"%{$user->id}%")->get();
    }
    public function getbytask($id){
        $user = Auth::user();
        $todo = todo::where('id_task',$id)->get();
        $sumtodo = $todo->count();
        $todo_done = $todo->filter(function($item){ return $item['status'] = true; })->count();
        $task = task::firstWhere('assigment','LIKE',"%{$user->id}%")->where('id_task',$id)->get();
        return response()->json([
            'status' => true,
            'data'   => [
                'data' => $task,
                'sum_todo' => $sumtodo,
                'todo_done'=> $todo_done

            ]
        ]);
    }
    public function destroy(task $task)
    {
        $name = $task->name;
        if($task->delete()){
            return response()->json([
                'status' => true,
                'message'=> `berhasil menghapus {$name}`,
            ]);
        }
        return response()->json([
            'status' => false,
            'message'=> `gagal menghapus {$name}`,
        ]);
    }
}
