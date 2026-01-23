<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Response\ResponseTrait;
use App\Http\Requests\Admin\User\UserRequest;
use App\Http\Resources\Admin\User\UserResource;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendInviteToUserMail;
use Illuminate\Support\Facades\Crypt;

class UserController extends Controller
{
    use ResponseTrait;
    public function showall(Request $request): \Illuminate\Http\JsonResponse
    {
      
        $data = User::orderByDESC('id');
        if(isset($request->search)){
            $data = $data->where('name','like',$request->search.'%')
                         ->Orwhere('email','like',$request->search.'%');
                      
        }
        if(isset($request->status)){
            $data = $data->where('status',$request->status);
        }
        if(isset($request->role)){
            $userRole = $request->role;
            $data = $data->whereHas('roles',function($q) use($userRole){
                return $q->where('name','like','%'.$userRole.'%');
            });
        }
        //Exclue user with "Super Admin" role from users table
        $data = $data->whereHas('roles',function($q) {
                    return $q->whereNot('name','Admin');
                });
        
        if(isset($request->per_page)){
            $per_page = $request->per_page; 
        }else {
            $per_page = '10';
        }
        $data = $data->paginate($per_page);
        if(empty($data)){
            return $this->jsonResponseFail(trans('common.no_record_found'),401);  
        }
        return $this->jsonResponseSuccess(['user'=> UserResource::collection($data)->response()->getData(true)]);
    }
    public function show(Request $request,$id): \Illuminate\Http\JsonResponse
    {
      
        $user = User::find($id);
        $roles = Role::WhereNot('name','Admin')->pluck('name');
        if(!$user){
            return $this->jsonResponseFail(trans('common.no_record_found'),401);
        }
    
        return $this->jsonResponseSuccess(['user'=>new  UserResource($user),'roles'=>$roles]);
    }

    public function store(UserRequest $request)  //: \Illuminate\Http\JsonResponse
    {
     
        $data = $request->validated();
      
        $user = User::create($data);
        $data['role']='User';

        $user->assignRole($data['role']);
        if(!empty($user)){
            $token = \Str::random(60);
            $expiration = now()->addMinutes(60);  // Token expires in 60 minutes
            $encryptedToken = Crypt::encryptString("{$token}|{$user->email}|{$expiration}");

            Mail::to($user->email)->send(new SendInviteToUserMail($user,$encryptedToken));  
            return $this->jsonResponseSuccess(
                $user
            );
        }
        else
        {
            return $this->jsonResponseFail(
                trans('common.something_went_wrong'),
                401
            );
        }
    }
    public function update(UserRequest $request,$id) : \Illuminate\Http\JsonResponse
    {
     
        $data = $request->validated();
    
        $user = User::find($id);
        $data['role']='User';
        if(!empty($user)){
            $user->update($data);
            if(!empty($user->roles)){
                foreach($user->roles as $role){
                    $user->removeRole($role);
                }
             }
            $user->assignRole($data['role']);
            return $this->jsonResponseSuccess(
              ['user'=> new UserResource($user)]
            );
        }
        else
        {
            return $this->jsonResponseFail(
                trans('common.no_record_found'),
                401
            );
        }
    }

    public function destroy($id){
      
        $user = User::find($id);
        if($user){
           
            $user->delete();
            return $this->jsonResponseSuccess(
                trans('common.deleted')
            );
        }
        else
        {
            return $this->jsonResponseFail(
                trans('common.no_record_found'),
                401
            );
        }
    }

    public function updateStatus(Request $request,$id){
        $data = $request->all();
        $user = User::find($id);
        if(!$user)
        {
            return $this->jsonResponseFail(trans('common.no_record_found'),401);
        }
        $user->status = $data['status'];
        $user->save();
     
        return $this->jsonResponseSuccess(trans('common.status_updated'));
    }
}
