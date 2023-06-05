<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Log;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
class AuthController extends Controller
{

    public function register(Request $request)
    {
        $password = $request->get("password");
        $hash = Hash::make($password);

        $object = \App\Models\User::where("email",$request->email)->first();
        if(!$object){
            $object_2 = \App\Models\User::where("nickname",$request->nickname)->first();
            if(!$object_2 and $request->nickname != ""){
                $user = new \App\Models\User();
                $user->email = $request->email;
                $user->first_name = $request->get("first_name");
                $user->last_name = $request->get("last_name");
                $user->nickname = $request->get("nickname");
                $user->password = $hash;
                $user->save();

                Auth::loginUsingId($user->id, true);

                $object = new \App\Models\Reader;
                if ($object) {
                    $object->user_id = auth()->user()->id;
                    $object->follows = 1;
                    $object->save();
                }

                return response()->json([
                    'data' => [
                        'message' => '',
                        'status' => 'success',
                        'response' => "",
                    ]
                ], 200);
            } else {
                return response()->json([
                    'data' => [
                        'message' => 'Nickname has issues, please try another',
                        'status' => 'error',
                        'response' => "",
                    ]
                ], 200);
            }

        } else {
            return response()->json([
                'data' => [
                    'message' => 'User already exists!',
                    'status' => 'error',
                    'response' => "",
                ]
            ], 200);
        }

    }

    public function login(Request $request)
    {
      $credentials = $request->only(['email', 'password']);

      if (auth()->attempt($credentials)) {

        print_r(Auth::check());
        exit();

        $user = auth()->user();

        return response()->json([
            'data' => [
                'message' => '',
                'status' => 'success',
                'response' => "",
            ]
        ], 200);

        // return response()->json(['error' => 'Unauthorized'], 401);
      }

      return response()->json([
        'data' => [
            'message' => 'Wrong username or password',
            'status' => 'error',
            'response' => "",
        ]
    ], 200);
    }

    public function logout(Request $request)
    {
        Auth::logout();


        return response()->json([
            'data' => [
                'message' => '',
                'status' => 'success',
                'response' => "",
            ]
        ], 200);
    }


    protected function respondWithToken($token,$user_id)
    {
      return response()->json([
        'user_id' => $user_id,
        'access_token' => $token,
        'token_type' => 'bearer',
        'expires_in' => auth('api')->factory()->getTTL() * 60
      ]);
    }
}
