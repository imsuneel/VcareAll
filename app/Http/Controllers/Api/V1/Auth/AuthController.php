<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller{

    public function login(Request $request){
        try{
            $validator = \Validator::make($request->all(), [
                'email'=> 'required|email|exists:users,email',
                'password'=> 'required'
            ]);
            if ($validator->fails()) {
                foreach ($validator->messages()->getMessages() as $field_name => $messages){
                    $errors[$field_name] = $messages['0'];
                }
                return response()->json(['errors'=>$errors])->setStatusCode(422);
            }else{
                // JWT
                $credentials = request(['email', 'password']);

                if (! $token = auth()->attempt($credentials)) {
                    return response()->json(['error' => 'Unauthorized'], 401);
                }
                $token_detail = $this->respondWithToken($token);
                return response()->json(['data'=>$token_detail])->setStatusCode(200);
            }
        }catch(\Exception $e){
            return response()->json(['error'=>trans('api.internal_server_error')])->setStatusCode(500);
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        try{
            $validator = \Validator::make($request->all(), [
                'name'=> 'required|string',
                'email'=> 'required|email|unique:users,email',
                'phone'=>'required|numeric|digits:10|unique:users,phone',
                'password'=> 'required|confirmed|min:6',
                'profile_image'=>'required|image|mimes:jpeg,jpg,png|required|max:10000'
            ]);
            if ($validator->fails()) {
                foreach ($validator->messages()->getMessages() as $field_name => $messages){
                    $errors[$field_name] = $messages['0'];
                }
                return response()->json(['errors'=>$errors])->setStatusCode(422);
            }else{
                $image = $request->file('profile_image');
                $profile_image = rand() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('profile_image'), $profile_image);
                $user = new \App\User();
                $user->name = $request->name;
                $user->email = $request->email;
                $user->phone = $request->phone;
                $user->password = bcrypt($request->password);
                $user->profile_image = $profile_image;
                $user->name = $request->name;
                $user->save();
                // JWT
                $credentials = request(['email', 'password']);

                if (! $token = auth()->attempt($credentials)) {
                    return response()->json(['error' => 'Unauthorized'], 401);
                }
                $token_detail = $this->respondWithToken($token);
                return response()->json(['data'=>$token_detail])->setStatusCode(200);
            }
        }catch(\Exception $e){
            return response()->json(['error'=>trans('api.internal_server_error')])->setStatusCode(500);
        }
    }

    protected function respondWithToken($token){
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];
    }
    
}
