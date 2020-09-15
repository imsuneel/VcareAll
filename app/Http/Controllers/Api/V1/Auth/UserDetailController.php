<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserDetailController extends Controller
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
            $details = $user_info->details;
            if($details){
                return new \App\Http\Resources\UserDetails($details);
            }
            return response()->json(['error'=>trans('api.not_found')])->setStatusCode(404);
            

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
                'father_name'=> 'required|string',
                'mother_name'=> 'required|string',
                'wife_name'=> 'required|string',
                'child_name'=> 'required|string',
                'address'=> 'required|string',
                'country_id'=>'required|numeric|exists:countries,id',
                'state_id'=>'required|numeric|exists:states,id',
                'city_id'=>'required|numeric|exists:cities,id',
                'zip_code'=>'required|numeric|digits:6',
            ]);
            if ($validator->fails()) {
                foreach ($validator->messages()->getMessages() as $field_name => $messages){
                    $errors[$field_name] = $messages['0'];
                }
                return response()->json(['errors'=>$errors])->setStatusCode(422);
            }else{
                $user_id = $request->user()->id;
                $user_info = \App\User::find($user_id);
                $user_detail = ['father_name'=> $request->father_name,
                                'mother_name'=> $request->mother_name,
                                'wife_name'=> $request->wife_name,
                                'child_name'=> $request->child_name,
                                'address'=> $request->address,
                                'country_id'=>$request->country_id,
                                'state_id'=>$request->state_id,
                                'city_id'=>$request->city_id,
                                'zip_code'=>$request->zip_code
                            ];
                $details = $user_info->details()->updateOrCreate(['user_id' => $user_id], $user_detail);
                $user_info = \App\User::find($user_id);
                return new \App\Http\Resources\UserDetails($user_info->details);
                
            }
            

        }catch(\Exception $e){
            dd($e->getMessage());
            return response()->json(['error'=>trans('api.internal_server_error')])->setStatusCode(500);
        }
    }

    
}
