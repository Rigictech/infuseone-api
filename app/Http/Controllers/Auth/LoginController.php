<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Response\ResponseTrait;
use App\Traits\Response\CommonTrait;
use App\Http\Requests\Auth\LoginRequest;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Crypt;




class LoginController extends Controller
{
    use ResponseTrait,CommonTrait;
    public function login(LoginRequest $request){
        $userLogin = false;    
        $user = User::where('email',$request['email'])->first();
 
        if (! $user || ! Hash::check($request['password'], $user->password)) {
            return $this->jsonResponseFail(trans('session.failed'),401);
        }

        if(!$user->status){
            return $this->jsonResponseFail(trans('auth.account_inactive'),401);
        }
        $userRole = $user->getRoleNames();
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

    public function changePassword(ChangePasswordRequest $request)
    {  
      
        $user = $request->user(); 
        if (!Hash::check($request->current_password, $user->password)) {
            return $this->jsonResponseFail(['message' => trans('common.password_incorrect')]);
        }
 
        $user->password = Hash::make($request->new_password);
        $user->save();

       
        return $this->jsonResponseSuccess(['message' => trans('common.password_updated')]);
    }

     public function profile(Request $request)
    {
        $user = $request->user();  
        return $this->jsonResponseSuccess(['data'=>$user]);
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
            trans('session.logout_failed')
        );
    }


    public function updateProfile(Request $request)
    { 
        $user = $request->user(); 
       
        if($user){
            $data = [
                'name' => $request->name,
                'email' => $request->email,
            ];
           
            $user->update($data);
        
            if ($user) {
                return $this->jsonResponseSuccess(['message' => trans('common.updated')]);
            }else{
                return $this->jsonResponseFail(['message' => trans('common.failed')]);
            }
        }else{
            return $this->jsonResponseFail(['message' => trans('common.failed')]);
        } 
    }

    public function updateProfileImage(Request $request)
    { 
       $user = $request->user(); 
       
        if($user){
            $data = [
                'profile_image' => $request->profile_image
            ];
          
            if(!empty($data['profile_image'])){
             $data['profile_image'] =  $this->saveProfileImage($data['profile_image']);
            }else{
                $data['profile_image'] =  $user->profile_image;
            }
            $user->update($data);
        
            if ($user) {
                return $this->jsonResponseSuccess(['message' => trans('common.updated')]);
            }else{
                return $this->jsonResponseFail(['message' => trans('common.failed')]);
            }
        }else{
            return $this->jsonResponseFail(['message' => config('common.failed')]);
        } 
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();
         // Encrypt token along with expiration time
        $token = \Str::random(60);
        $expiration = now()->addMinutes(60);  // Token expires in 60 minutes
        $encryptedToken = Crypt::encryptString("{$token}|{$request->email}|{$expiration}");

        
        if ($user) { 

            Mail::to($user->email)->send(new ResetPasswordMail($encryptedToken));
            return $this->jsonResponseSuccess(['message' => trans('common.MAIL_SEND_SUCCESS')]);
        }
        return $this->jsonResponseFail(['message' => trans('common.USER_NOT_FAILED')]);
    
    
    }
   
    public function resetPassword(ResetPasswordRequest $request)
    {
     
       // Decrypt the token to get the email and expiration
        try {
         $decrypted = Crypt::decryptString($request->token);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return $this->jsonResponseFail(['message' => trans('common.INVALID_TOKEN')]);
        }

        list($token, $email, $expiration) = explode('|', $decrypted);

        if (now()->greaterThan($expiration)) {
            return $this->jsonResponseFail(['message' => trans('common.TOKEN_EXPIRED')]);
        }

        $user = User::where('email', $email)->first();
       
        if ($user) {
            $user->update([
                'password' => Hash::make($request->password),
                ]);             
               
            return $this->jsonResponseSuccess(['message' => trans('common.PASSWORD_RESET_SUCCESS')]);
        }
        return $this->jsonResponseFail(['message' =>  trans('common.EMAIL_FAILED')]);
    }
}
