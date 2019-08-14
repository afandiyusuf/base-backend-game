<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','no_hp','settings'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */


    public $CAN_BE_VALIDATED = 1;
    public $CANNOT_BE_VALIDATED = 2;
    public $ALREADY_VALIDATED = 3;

    protected $hidden = ['password', 'remember_token','confirmation_code','confirmed'];
    
    public function Statistic()
    {
        return $this->hasMany('App\Models\StatisticPlayer','user_id');
    }
    public function BestScore()
    {
        return $this->hasOne('App\Models\GameRecordScore','user_id');
    }
    public function GameSessions()
    {
        return $this->hasMany('App\Models\GameSession','user_id');
    }

    public function loginWithUsername($username,$password)
    {
        if(Auth::attempt(['username' => $username, 'password' => $password])){
            $access_token = $this->generateRandomString(200);

            $user = $this->where('id',Auth::user()->id)->first();
            $user->access_token = $access_token;
            
            $user->save();
            
            return $user;

        }else{
            return null;
        }

    }

    public function requestAccessToken($username)
    {
        $access_token = $this->generateRandomString(200);
        
        if($this->where('username',$username)->count() > 0){
            $this->where('username',$username)->update(['access_token'=>$access_token]);
            $user = $this->where('username',$username)->first();
        }else{
            $user = new User();
            $user->username = $username;
            $user->access_token = $access_token;
            $user->save();
        }

        return $user;
    }

    public function setGender($id,$gender)
    {
        //get user by id
        $this->where('id',$id)->update(['gender'=>$gender]);
    }

    public function checkValidate($username,$no_hp)
    {
        $data_found = $this->where('username',$username)
        ->where('no_hp',$no_hp)->first();
        
        if($data_found == NULL)
        {
            return $this->CANNOT_BE_VALIDATED;
        }else if($data_found->confirmed == 1)
        {
            return $this->ALREADY_VALIDATED;
        }else{
            return $this->CAN_BE_VALIDATED;
        }
    }

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function getUserByUsernameAndPhone($username, $phone)
    {
        $user = $this->where('username',$username)
        ->where('no_hp',$phone)->first();

        return $user;


    }

    public function getUserByAccessToken($access_token)
    {
        $user = $this->where('access_token',$access_token)
        ->first();

        return $user;
    }

    public function update_setting($id, $data)
    {
        $user = $this->where('id',$id)->first();
        $user->settings = preg_replace('/(^[\"\']|[\"\']$)/', '', $data);
        $user->update();
    }

    public function createUser($request)
    {
        $user = new User();
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->access_token = $this->generateRandomString(200);

        if($request->has('email'))
        {
            $user->email = $request->email;
        }
        if($request->has('name'))
            $user->name = $request->name;
        if($request->has('settings'))
            $user->settings = $request->settings;
        if($request->has('location_id'))
            $user->location_id = $request->location_id;
        
        $user->save();
        return $user;
    }

    public function updateUser($request,$user)
    {
        $updateData = [];
        $updateData['username'] = $request->username;

        if($request->has('password'))
        {
            $updateData['password'] = Hash::make($request->password);
        }
        if($request->has('email'))
        {
            $updateData['email'] = $request->email;
        }
        if($request->has('name'))
            $updateData['name'] = $request->name;
        if($request->has('settings'))
            $updateData['settings'] = $request->settings;
        if($request->has('location_id'))
            $updateData['location_id'] = $request->location_id;
        
        $user->update($updateData);
        return $user;
    }
    
}
