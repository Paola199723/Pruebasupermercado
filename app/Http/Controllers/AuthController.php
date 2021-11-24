<?php

namespace App\Http\Controllers;

use App\Models\User;
//use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * registra un ussuario
     * @param{Request} $request
     * @return{User}
     */
    public function register(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|min:5',
        ]);
        if ($validation->fails()){
            return response()->json([ 'message'=> 'Form Invalid',
             'errors' => $validation->errors() ], 400);
        }
        $user = new User($request->all());
        $user->password = Hash::make($user->password);
        $user->save();
        return response()->json($user);

    }
    /**
     * obtiene los datos de un usuario para loguearse
     * @param{Request} $request
     * @return{JSON}
     */
    public function login(Request $request){

        $validation = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);
        if ($validation->fails()){
            return response()->json([ 'message'=> 'Form Invalid',
             'errors' => $validation->errors() ], 400);
        }
        $credentials = $request->only('email', 'password');
    try {
        if (! $token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'invalid_credentials'], 400);
        }
    } catch (JWTException $e) {
        return response()->json(['error' => 'could_not_create_token'], 500);
    }
    return response()->json([
        'token' => $token,
        'user' => JWTAuth::user()
    ]);

    }
}
