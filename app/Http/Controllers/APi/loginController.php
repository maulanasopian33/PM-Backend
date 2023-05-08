<?php

namespace App\Http\Controllers\APi;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;

class loginController extends Controller
{
    public function index(Request $req){

        $credentials = $this->getCredentials($req);
        if(!Auth::validate($credentials)):
            return response()->json([
                'status'  => false,
                'message' => "Validasi Credential Gagal"
            ], 401);
        endif;

        $user = Auth::getProvider()->retrieveByCredentials($credentials);
        if (Auth::attempt($credentials)) {
            $token = $req->user()->createToken('API Token')->accessToken;
            return response()->json([
                'status' => true,
                'data' => [
                    'token' => $token
                ]
            ]);
        }

        return response()->json([
            'status'  => false,
            'message' => "Login gagal"
        ], 401);

    }
    public function getCredentials($req){
        $username = $req->email;
        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            return [
                'email' => $username,
                'password' => $req->password
            ];
        }

        return [
            'username' => $username,
            'password' => $req->password
        ];
    }

    public function whois(){
        return response()->json(Auth::user());
    }
}
