<?php

namespace App\Http\Controllers;

use App\Models\workspacechat;
use App\Events\chat as EventsChat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WorkspacechatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        return response()->json([
            "data"      => workspacechat::where('workspace_id',$id)->orderBy('created_at','asc')->get(),
            "status"    => true
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $req, $channel)
    {
        $mytime = Carbon::now();
        // return $mytime->toDateTimeString();
        try{
            if($req->type === 'file'){
                $file = $req->msg->move(public_path('uploads/image'), $req->msg->getClientOriginalName());
                // $image_path = $req->file('avatar')->store('image', 'public');
                $image_path = '/uploads/image/'.$req->msg->getClientOriginalName();
                workspacechat::create([
                    'id_chat' => Str::uuid()->toString(),
                    'workspace_id' => $channel,
                    'message' => $image_path,
                    'from'    => $req->from,
                    'type'    => $req->type,
                    'reply'   => $req->reply,
                    'time'    => $mytime->format('Y-m-d H:i:s')
                ]);
                EventsChat::dispatch($channel,[
                    'message' => $image_path,
                    'from'    => $req->from,
                    'type'    => $req->type,
                    'reply'   => $req->reply,
                    'time'    => $mytime->toDateTimeString()
                ]);
                return response()->json([
                    'status' => true,
                    'message'   => $image_path
                ]);
            }else{
                workspacechat::create([
                    'id_chat' => Str::uuid()->toString(),
                    'workspace_id' => $channel,
                    'message' => $req->msg,
                    'from'    => $req->from,
                    'type'    => $req->type,
                    'reply'   => $req->reply,
                    'time'    => $mytime->format('Y-m-d')
                ]);
                EventsChat::dispatch($channel,[
                    'message' => $req->msg,
                    'from'    => $req->from,
                    'type'    => $req->type,
                    'reply'   => $req->reply,
                    'time'    => $mytime->toDateTimeString()
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
    public function show(workspacechat $workspacechat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, workspacechat $workspacechat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(workspacechat $workspacechat)
    {
        //
    }
}
