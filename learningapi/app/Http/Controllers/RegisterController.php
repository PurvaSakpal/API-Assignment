<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware("jwtAuth",["except"=>["login","register"]]);
    }
    public function Register(Request $req){
        $validator=Validator::make($req->all(),[
            'name'=>'required|min:2',
            'email'=>'required|email',
            'password'=>'required|min:6|max:15'
        ]);
        if($validator->fails())
        {
            return response()->json($validator->errors());
        }
        else{
            $user=new User();
            $user->name=$req->name;
            $user->email=$req->email;
            $user->password=Hash::make($req->password);
            if($user->save()){
                return response()->json(['msg'=>"Registered Successfully",'user'=>$user]);
            }
            else{
                return response()->json(['msg'=>"Error while registering"]);
            }
        }
    }
    public function Users(){
        $users=User::latest()->get();
        return response()->json($users);
    }
    public function Login(Request $req){
        $validate=Validator::make($req->all(),[
            'email'=>'required|email',
            'password'=>'required'
        ]);
        // if($validate){
        //     $user=User::where(['email'=>$req->email])->first();
        //     if(!empty($user)){
        //         if($req->password == $user->password){
        //             return response()->json(['msg'=>"Login Successfull"]);
        //         }
        //         else
        //         {
        //             return response()->json(['msg'=>"Login not Successfull"]);
        //         }
        //     }
        //     else{
        //         return response()->json(['msg'=>"Email not registered"]);
        //     }
        // }
        if($validate->fails())
        {
            return response()->json($validate->errors());
        }

        if(! $token = auth()->attempt($validate->validated()))
        {
            return response()->json(["Email  or password does not match !",401]);
        }

        return $this->respondWithToken($token);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(["logged out !"]);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            "access_token"=>$token,
            "token_type"=>"Bearer",
            "expires_in"=> auth()->factory()->getTTL()*60
        ]);
    }
}
