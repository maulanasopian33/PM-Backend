<?php

namespace App\Http\Controllers;

use App\Events\chat as EventsChat;
use App\Models\chat;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        return response()->json([
            "data"      => Chat::where('task_id',$id)->orderBy('created_at','asc')->get(),
            "status"    => true
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
    public function store(Request $req, $channel)
    {
        $mytime = Carbon::now();
        try{
            if($req->type === 'file'){
                $file = $req->msg->move(public_path('uploads/image'), $req->msg->getClientOriginalName());
                // $image_path = $req->file('avatar')->store('image', 'public');
                $image_path = '/uploads/image/'.$req->msg->getClientOriginalName();
                chat::create([
                    'id_chat' => Str::uuid()->toString(),
                    'task_id' => $channel,
                    'message' => $image_path,
                    'from'    => $req->from,
                    'type'    => $req->type,
                    'reply'   => $req->reply,
                    'time'    => $mytime->toDateTimeString()
                ]);
                EventsChat::dispatch($channel,[
                    'message' => $image_path,
                    'from'    => $req->from,
                    'type'    => $req->type,
                    'reply'   => $req->reply,
                    'time'    => $mytime
                ]);
                return response()->json([
                    'status' => true,
                    'message'   => $image_path
                ]);
            }else{
                chat::create([
                    'id_chat' => Str::uuid()->toString(),
                    'task_id' => $channel,
                    'message' => $req->msg,
                    'from'    => $req->from,
                    'type'    => $req->type,
                    'reply'   => $req->reply,
                    'time'    => $mytime->toDateTimeString()
                ]);
                EventsChat::dispatch($channel,[
                    'message' => $req->msg,
                    'from'    => $req->from,
                    'type'    => $req->type,
                    'reply'   => $req->reply,
                    'time'    => $mytime
                ]);
                return response()->json([
                    'status' => true,
                    'message'   => 'ok'
                ]);
            }

        }catch(\Exception $e){
            return $e;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(chat $chat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(chat $chat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, chat $chat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(chat $chat)
    {
        //
    }
}
