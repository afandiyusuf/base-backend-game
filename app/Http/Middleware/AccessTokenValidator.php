<?php

namespace App\Http\Middleware;

use Closure;
use App\ReturnData;
use App\User;

class AccessTokenValidator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->retData = new ReturnData();
        $token = $request->bearerToken();

        if(!$token)
        {
            $this->retData->set('access token tidak ada',500,null);
            return response()->json($this->retData,$this->retData->code);
        }else{
            $userModel = new User();
            $user = $userModel->getUserByAccessToken($token);
            
            if(!$user)
            {
                $this->retData->set('access token tidak valid',500,null);
                return response()->json($this->retData,$this->retData->code);
            }else{
                return $next($request);
            }
        }
        
    }
}
