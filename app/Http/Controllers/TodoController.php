<?php

namespace App\Http\Controllers;

use App\Models\task;
use App\Models\todo;
use App\Services\PayUService\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {

        return response()->json(todo::where('id_task',$id)->get());
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
    public function store(Request $request)
    {

        try{

            todo::create([
                'id_task'   => $request->id_task,
                'name'      => $request->name,
                'status'    => $request->status
            ]);
            // logging
            Log::channel('ActionRequest')->info($request->from." add new todo [".$request->name."] in Route ".$request->getPathInfo()." with parameters: ".$request);

            $todo = todo::where('id_task',$request->id_task)->get();
            $sumtodo = $todo->count();
            $todo_done = $todo->filter(function($item){ return $item->status == true; })->count();
            $data = task::where('id_task',$request->id_task)->get();
            if($sumtodo == 0) {
                $data[0]->status = "created";
                $data[0]->save();
            }else{
                if($todo_done == $sumtodo){
                    $data[0]->status = "finished";
                    $data[0]->save();
                }else{
                    $data[0]->status = "OnProgress";
                    $data[0]->save();
                }
            }
            return response()->json([
                'status'  => true,
                'message' => 'created',
            ],200);
        }catch(\Exception $e){
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ],400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(todo $todo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(todo $todo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updates(Request $request)
    {
        // return $request->name;
        try{
            $data =todo::where('name',$request->name)->get();
            // return $data;
            $data[0]->status = $request->status;
            $data[0]->save();
            $todo = todo::where('id_task',$request->id_task)->get();
            $sumtodo = $todo->count();
            $todo_done = $todo->filter(function($item){ return $item->status == true; })->count();
            $data =task::where('id_task',$request->id_task)->get();
            if($sumtodo == 0) {
                $data[0]->status = "created";
                $data[0]->save();
            }else{
                if($todo_done == $sumtodo){
                    $data[0]->status = "finished";
                    $data[0]->save();
                }else{
                    $data[0]->status = "OnProgress";
                    $data[0]->save();
                }
            }
            return response()->json([
                'status'  => true,
                'message' => 'Updated',
            ],200);
        }catch(\Exception $e){
            return response()->json([
                'status'  => false,
                'message' => 'Notfound',
            ],404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        try{
            $todos = todo::find($id);
            $hapus = todo::find($id)->delete();
                if($hapus){
                    $todo = todo::where('id_task',$todos['id_task'])->get();
                    $sumtodo = $todo->count();
                    $todo_done = $todo->filter(function($item){ return $item->status == true; })->count();
                    $data =task::where('id_task',$todos['id_task'])->get();
                    if($sumtodo == 0) {
                        $data[0]->status = "created";
                        $data[0]->save();
                    }else{
                        if($todo_done == $sumtodo){
                            $data[0]->status = "finished";
                            $data[0]->save();
                        }else{
                            $data[0]->status = "OnProgress";
                            $data[0]->save();
                        }
                    }
                    return response()->json([
                        'status'  => true,
                        'message' => 'Deleted',
                    ],200);
                }else{
                    return response()->json([
                        'status'  => false,
                        'message' => 'Delete Failed',
                    ],200);
                }
        }catch(\Exception $e){
            return response()->json([
                'status'  => false,
                'message' => $e,
            ],404);
        }

    }
}
