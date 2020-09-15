<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        try{
            $user_id = $request->user()->id;
            $user_info = \App\User::find($user_id);
            return new \App\Http\Resources\UserProfile($user_info);

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
    public function store(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(), [
                'name'=> 'required|string',
                'email'=> 'required|email',
                'phone'=>'required|numeric|digits:10',
                'password'=> 'required|confirmed|min:6',
                'profile_image'=>'required|image|mimes:jpeg,jpg,png|required|max:10000'
            ]);
            if ($validator->fails()) {
                foreach ($validator->messages()->getMessages() as $field_name => $messages){
                    $errors[$field_name] = $messages['0'];
                }
                return response()->json(['errors'=>$errors])->setStatusCode(422);
            }else{
                $user_id = $request->user()->id;
                $user = \App\User::find($user_id);
                $image = $request->file('profile_image');
                $profile_image = rand() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('profile_image'), $profile_image);
                $user->name = $request->name;
                $user->email = $request->email;
                $user->phone = $request->phone;
                $user->password = bcrypt($request->password);
                $user->profile_image = $profile_image;
                $user->name = $request->name;
                $user->save();
                return new \App\Http\Resources\UserProfile($user);
            }
            

        }catch(\Exception $e){
            return response()->json(['error'=>trans('api.internal_server_error')])->setStatusCode(500);
        }
    }

    
}
