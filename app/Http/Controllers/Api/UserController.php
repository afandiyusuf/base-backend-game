<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Validator;

use App\ReturnData;

class UserController extends Controller
{
    public $userModel;
    public $retData;

    public function __construct()
    {
        $this->retData = new ReturnData();
        $this->userModel = new User();
    }
    public function login(Request $request)
    {
        $data = Validator::make($request->all(),[
            'username'=> 'required',
            'password' => 'required',
            'gender'   => 'required'
        ]);

        if ($data->fails()) {
            
            $this->retData->set(__('api.parameter_not_completed'),500,[]);

        }else{
             
            $userFound = $this->userModel->loginWithUsername($request->username,$request->password);

            if($userFound != null)
            {
                //update gender based on login
                $this->userModel->setGender($userFound->id,$request->gender);
                $userFound->gender = $request->gender;
                $this->retData->set(__('api.success'),200,$userFound);
            }else{
                $this->retData->set(__('api.username_password_error'),500,$userFound);
            }
        }

        return response()->json($this->retData,$this->retData->code);

        
    }

    public function requestAccessToken(Request $request)
    {
        $data = Validator::make($request->all(),[
            'username'=> 'required'
        ]);

        if ($data->fails()) {
            
            $this->retData->set(__('api.parameter_not_completed'),500,[]);

        }else{
             
            $userFound = $this->userModel->requestAccessToken($request->username,$request->password);

            if($userFound != null)
            {
                $this->retData->set(__('api.success'),200,$userFound);
            }else{
                $this->retData->set(__('api.username_password_error'),500,$userFound);
            }
        }

        return response()->json($this->retData,$this->retData->code);
    }

    public function update(Request $request)
    {
  
        $user = $this->userModel->getUserByAccessToken($request->bearerToken());
        $user = $this->userModel->updateUser($request,$user);
        $this->retData->set(__('api.success'),200,$user);


        return response()->json($this->retData,$this->retData->code);
    }

    public function check_validate(Request $request)
    {
        $data = Validator::make($request->all(),[
            'username' => 'required',
            'no_hp' => 'required'
        ]);
        
        if($data -> fails()){
            $this->retData->set(__('api.parameter_not_completed'),500,[]);
        }else{
            $data = $this->userModel->checkValidate($request->username,$request->no_hp);
            
            //cek hasil dari validasi
            //data bisa divalidasi
            if($data == $this->userModel->CAN_BE_VALIDATED)
            {
                $this->retData->set(__('api.can_be_validated'),200,[]);
            //data tidak bisa divalidasi
            }else if($data == $this->userModel->CANNOT_BE_VALIDATED){
                $this->retData->set(__('api.cannot_be_validated'),500,[]);
            //data sudah divalidasi
            }else if($data == $this->userModel->ALREADY_VALIDATED){
                $this->retData->set(__('api.already_validated'),500,[]);
            }

            
        }
        return response()->json($this->retData,$this->retData->code);
    }

    public function validate_user(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'username' => 'required',
            'no_hp' => 'required',
            'password' => 'required',
            'nickname' => 'required',
            'location_id' => 'required'
        ]);
        
        if($validator -> fails()){
            $this->retData->set(__('api.parameter_not_completed'),500,[]);
        }else{
            
            $data = $this->userModel->checkValidate($request->username,$request->no_hp);

            if($data == $this->userModel->CAN_BE_VALIDATED)
            {
                $user = $this->userModel->getUserByUsernameAndPhone($request->username,$request->no_hp);
               
                $user->no_hp = $request->no_hp;
                $user->password = Hash::make($request->password);
                $user->name = $request->nickname;
                $user->location_id = $request->location_id;
                $user->confirmed = 1;
                $user->save();

                $userFound = $this->userModel->loginWithUsername($request->username,$request->password);
                
                if($userFound != null)
                {
                    $this->retData->set(__('api.success'),200,$userFound);
                }else{
                    $this->retData->set(__('api.username_password_error'),500,$userFound);
                }
                
            }else if($data == $this->userModel->CANNOT_BE_VALIDATED){
                $this->retData->set(__('api.cannot_be_validated'),500,[]);
            }else if($data == $this->userModel->ALREADY_VALIDATED){
                $this->retData->set(__('api.already_validated'),500,[]);
            }
              
            
        }

        return response()->json($this->retData,$this->retData->code);
    }

    public function update_setting(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'settings' => 'required'
        ]);
        
        if($validator -> fails()){
            $this->retData->set(__('api.parameter_not_completed'),500,[]);
        }else{

            
            
            $user = $this->userModel->getUserByAccessToken($request->bearerToken());

            $userModel = new User();
            $userModel->update_setting($user->id,$request->settings);
            
            $this->retData->set(__('api.success'),200,[]);
        }

        return response()->json($this->retData,$this->retData->code);
        
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'username' => 'required|unique:users',
            'password' => 'required',
            'email' => 'required|unique:users'
        ]);
        
        if($validator -> fails()){
            $this->retData->set(__('api.parameter_not_completed'),500,[]);
        }else{
            $user = $this->userModel->createUser($request);
            $this->retData->set(__('api.success'),200,$user);
        }

        return response()->json($this->retData,$this->retData->code);
    }
}
