<?php

namespace App\Http\Controllers;

use DB;
use Validator;
use Log;
use Auth;
use Hash;
use App\Models\User;
use App\Models\Parents;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => 'bail|required|string|max:64',
            'password' =>  'bail|required|string|max:64',
        ]);

        if($validator->fails()){
            return $this->jsonResult(400, $validator->errors());
        }

       if(!Auth::attempt([
           'name' => $request->username,
           'password' => $request->password
           ])
        ){
            $validator->errors()->add('password', 'Incorrect username or password');
            return $this->jsonResult(400, $validator->errors());
        }

        return $this->jsonResult(200, 'success', 'dashboard');
    }

    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => 'bail|required|string|max:64|unique:users,name',
            'password' =>  'bail|required|string|max:64',
            'address' => 'bail|required|string|max:64',
            'email' => 'bail|required|email|unique:users,email',
            'phone' => 'bail|required|string|max:20',
            'id_number' => 'bail|required|numeric',
        ]);

        if($validator->fails()){
            return $this->jsonResult(400, $validator->errors());
        }

        return DB::transaction(function () use ($request) {
            try{
                $user = User::create([
                    'name' => $request->username,
                    'password' => Hash::make($request->password),
                    'email' => $request->email,
                    'role' => 'parent',
                ]);

                Parents::create([
                    'user_id' => $user->id,
                    'address' => $request->address,
                    'id_number' => $request->id_number,
                    'phone' => $request->phone,
                ]);

                DB::commit();

                return $this->jsonResult(200, 'Success', 'login');
            } catch(\Throwable $th){
                DB::rollback();
                throw $th;

                return $this->jsonResult(500, 'An unknown error occured, please try again');
            }
        });
    }

}
