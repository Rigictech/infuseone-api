<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Response\ResponseTrait;
use App\Http\Requests\Auth\LoginRequest;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    use ResponseTrait;
    public function login(Request $request){
        $userLogin = false;    
        $user = User::where('email',$request['email'])->first();
 
        if (! $user || ! Hash::check($request['password'], $user->password)) {
            return $this->jsonResponseFail(trans('session.failed'),401);
        }

        if(!$user->status){
            return $this->jsonResponseFail(trans('auth.account_inactive'),401);
        }
        $userRole = '';
        //= $user->getRoleNames();
        if(empty($userRole[0])){
            $userLogin = [
                'user'=>$user,
                'type' => $userRole,
                'token' => $user->createToken(
                    $request['device_name']
                )->plainTextToken
            ];
        }
        else{
                $userLogin = [
                    'user'=>$user,
                    'type' => $userRole,
                    'token' => $user->createToken($request['device_name'],[$userRole[0]])->plainTextToken
                ];
        }
        return $this->jsonResponseSuccess($userLogin);
    }

    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        if(
            $request->user()->currentAccessToken()->delete()
        ){
            return $this->jsonResponseSuccess(
                trans('session.logout_success')
            );
        }

        return $this->jsonResponseSuccess(
            trans('session.logout_fail')
        );
    }
}
