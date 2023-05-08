<?php

namespace App\Http\Controllers\Api;

use App\Events\GlobalMessage;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Workspace;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use function PHPUnit\Framework\isNull;

class adminController extends Controller
{
    public function addteam(Request $req){
        $user = Auth::user();
        $admin = $user->admin;
        if(!$admin){
            return response()->json([
                'status' => false,
                'message'=> 'anda bukan admin'
            ]);
        }
        try{
            $data = User::create([
                'name'      => $req->name,
                'username'  => $req->username,
                'email'     => $req->email,
                'nomor'     => $req->nomor,
                'password'  => bcrypt($req->password,['rounds' => 15])
            ]);
            return response()->json([
                'status' => true,
                'message'=> 'berhasil menambahkan Team'
            ]);
        }catch(Exception $e){
            return response()->json([
                'status' => false,
                'message'=> 'gagal menambahkan Team',
                'data'   => $e
            ]);
        }
    }
    public function updatepassword(Request $req){
        $user = Auth::user();
        $admin = $user->admin;
        if(!$admin){
            return response()->json([
                'status' => false,
                'message'=> 'anda bukan admin'
            ]);
        }
        try{
            // return Hash::check('1q2w3e4r5t', $user->password);
            // return response()->json([
            //     "old" => $user->password,
            //     "new" => bcrypt($req->oldpassword,['rounds' => 15]),
            //     "cek" => Hash::check("1q2w3e4r5t", $user->password)
            // ]);
            if(Hash::check($req->oldpassword, $user->password)){
                $user->update([
                            'password'  => bcrypt($req->newpassword,['rounds' => 15])
                        ]);
                return response()->json([
                    'status' => true,
                    'message'=> 'berhasil mengupdate password'
                ]);
            }
        //     $password = bcrypt($req->oldpassword);
        //     if(is_null($req->password)){
        //         $password = $team->get()[0]->password;
        //     };
        //     $team->update([
        //         'name'      => $req->name,
        //         'username'  => $req->username,
        //         'email'     => $req->email,
        //         'nomor'     => $req->nomor,
        //         'password'  => $password
        //     ]);
        //     return response()->json([
        //         'status' => true,
        //         'message'=> 'berhasil mengupdate Team'
        //     ]);
        }catch(Exception $e){
            return response()->json([
                'status' => false,
                'message'=> 'gagal mengupdate Password',
                'data'   => $e->getMessage()
            ]);
        }
    }
    public function updateteam(Request $req){
        $user = Auth::user();
        // $admin = $user->admin;
        // if(!$admin){
        //     return response()->json([
        //         'status' => false,
        //         'message'=> 'anda bukan admin'
        //     ]);
        // }
        try{
            $team = User::where('id', $req->id);
            $password = bcrypt($req->password,['rounds' => 15]);
            if(is_null($req->password)){
                $password = $team->get()[0]->password;
            };
            $team->update([
                'name'      => $req->name,
                'username'  => $req->username,
                'email'     => $req->email,
                'nomor'     => $req->nomor,
                'password'  => $password
            ]);
            return response()->json([
                'status' => true,
                'message'=> 'berhasil mengupdate Team'
            ]);
        }catch(Exception $e){
            return response()->json([
                'status' => false,
                'message'=> 'gagal mengupdate Team',
                'data'   => $e->getMessage()
            ]);
        }
    }
    public function getteam(){
        return response()->json([
            'status' => true,
            'data'   => User::get()
        ]);
    }
    public function sendNotif(Request $req){
        $mytime = Carbon::now();
        GlobalMessage::dispatch([
            'message' => $req->message,
            'from'    => $req->from,
            'type'    => $req->type,
            'time'    => $mytime->toDateTimeString()
        ]);
        return response()->json([
            'status' => true,
            'message'=> "Notif Published"
        ]);
    }
    public function addworkspace(Request $req){
        $user = Auth::user();
        $admin = $user->admin;
        if(!$admin){
            return response()->json([
                'status' => false,
                'message'=> 'anda bukan admin'
            ]);
        }
        // return $req;
        try{
            $file = $req->avatar->move(public_path('uploads/image'), $req->avatar->getClientOriginalName());
            $image_path = '/uploads/image/'.$req->avatar->getClientOriginalName();
            $data = Workspace::create([
                'name'      => $req->name,
                'assigment' => $req->assigment,
                'deskripsi' => $req->deskripsi,
                'avatar'    => $image_path
            ]);
            return response()->json([
                'status' => true,
                'message'=> 'berhasil menambahkan workspace'
            ]);
        }catch(Exception $e){
            return response()->json([
                'status' => false,
                'message'=> 'gagal menambahkan gagal',
                'data'   => $e
            ]);
        }
    }
    public function getworkspace(){
        $user = Auth::user();
        if($user->admin){
            return response()->json(Workspace::get());
        }
        return response()->json(Workspace::where('assigment','LIKE',"%{$user->id}%")->orderBy('name','asc')->get());
    }

    public function updateworkspace(Request $req){
        $user = Auth::user();
        $admin = $user->admin;
        if(!$admin){
            return response()->json([
                'status' => false,
                'message'=> 'anda bukan admin'
            ]);
        }
        $workspace = Workspace::where('id',$req->id);
        $image_path = $workspace->get()[0]->avatar;
        if($req->avatar_updated === '1'){
            if(File::exists(public_path($image_path))){
                File::delete(public_path($image_path));
            }
            $file = $req->avatar->move(public_path('uploads/image'), $req->avatar->getClientOriginalName());
            $image_path = '/uploads/image/'.$req->avatar->getClientOriginalName();
            // return "dihapus";
        }
            // return $req->avatar_updated;
        try{
            $workspace->update([
                'name'      => $req->name,
                'assigment' => $req->assigment,
                'deskripsi' => $req->deskripsi,
                'avatar'    => $image_path
            ]);
            return response()->json([
                'status' => true,
                'message'=> 'berhasil update workspace'
            ]);
        }catch(Exception $e){
            return response()->json([
                'status' => false,
                'message'=> 'gagal update gagal',
                'data'   => $e->getMessage()
            ]);
        }
    }

    public function getworkspaceByName($name){
        return response()->json([
            "data" => Workspace::where('name',$name)->get()]);
    }

    public function destroyworkspace($id)
    {
        $workspace = Workspace::find($id);
        if(is_null($workspace)){
            return response()->json([
                'status' => false,
                'message'=> "workspace tidak di temukan",
            ],404);
         }
        $name = $workspace->name;
        $image_path  = public_path($workspace->avatar);
        // return File::exists($image_path);
        if(File::exists($image_path)) {
            if(!File::delete($image_path)){
                return response()->json([
                    'status' => false,
                    'message'=> "gagal menghapus",
                ],204);
            };
            // return $name;
            if($workspace->delete()){
                return response()->json([
                    'status' => true,
                    'message'=> 'berhasil menghapus '.$name,
                ]);
            }
            return response()->json([
                'status' => false,
                'message'=> "gagal menghapus",
            ],204);
        }
    }

    public function destroymember($id)
    {
        try{
            $hapus = User::find($id)->delete();
                if($hapus){
                    return response()->json([
                        'status'  => true,
                        'message' => "Deleted",
                    ]);
                }else{
                    return response()->json([
                        'status'  => false,
                        'message' => "Gagal Menghapus",
                    ]);
                }
        }catch(\Exception $e){
            return response()->json([
                'status'  => false,
                'message' => $e,
            ],404);
        }

    }
}
