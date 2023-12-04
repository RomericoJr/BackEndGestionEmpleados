<?php

namespace App\Http\Controllers;

use App\Models\rol;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{

       /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $token = JWTAuth::parseToken()->refresh();
        return $this->respondWithToken($token);
    }


        /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
{
    $expiration = JWTAuth::factory()->getTTL() * 60;

    return response()->json([
        'access_token' => $token,
        'token_type' => 'bearer',
        'expires_in' => $expiration,
        'expiration_date' => now()->addSeconds($expiration)->toDateTimeString(),
    ]);
}


    public function getUser(){
        $user = User::all();
        return response()->json($user);
    }

    public function register(Request $request){

        try{
            $validateUser = Validator::make($request->all(),[
                'name' => 'required|string|max:255',
                'NUE' => 'required|string|max:10|unique:users',
                'password'=> 'required|string|min:8',
                'id_rol' => 'required|integer'

            ]);

            if($validateUser->fails()){
                return response()->json([
                'status' => false,
                'message' => 'validation Error',
                'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'NUE'=> $request->NUE,
                'password'=> bcrypt($request->password),
                'id_rol' => $request->id_rol
            ]);

            $token = $user->createToken('TOKEN')->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'User created successfully',
                'data' => $user,
                'token'=> $this->respondWithToken($token),
            ], 201);
        } catch (\Throwable $th){
            return response()->json([
                'status' => false,
                'message' => 'User created failed',
                'errors' => $th->getMessage()
            ], 500);
        }
    }


    public function login(Request $request){
        if(!Auth::attempt($request->only('name', 'password')))
        {
            return response()
                ->json(['message'=>'Unauthorized'],401);
        }

        $user = User::where('name', $request['name'])
                ->addSelect(
                    [
                        'rol' =>rol::select('tipo')
                        ->whereColumn('id_rol','id')
                    ]
                )
                // ->with('agregmiado')
                ->firstOrFail();

        $token = JWTAuth::fromUser($user);
        // $token = $user->createToken('TOKEN')->plainTextToken;

        $cookie = cookie('cookie_token', $token,60 *24* 2 );

        return response()
            ->json([
                'message'=>'Hi',
                'name' => $user->name,

                'token'=> $this->respondWithToken($token),

                // 'access_token' => $token,
                // 'typeToken' => 'Bearer',
                'user' => $user
            ])
            ->withoutCookie($cookie);
    }

    // public function me(){
    //     return response()->json(auth()->user());
    // }

    public function userDetails()
    {
        $user = Auth::guard('api')->user();
        return response()->json( $user);
    }

    public function logout(Request $request){
          /**
         * @var user $user
        */
        $user = Auth::user();

        //Aun no hay JWT
        // $userToken = $user->tokens();
        // $userToken->delete();
        return response(['message'=> 'Logged Out!!'],200);
    }



}
