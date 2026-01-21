<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\Response\ResponseTrait;
use App\Http\Requests\Admin\FormStackUrl\FormStackRequest;
use App\Http\Resources\Admin\FormStackUrl\FormStackResource;
use App\Models\FormStackUrl;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class FormStackController extends Controller
{
    use ResponseTrait;
    public function showall(Request $request): \Illuminate\Http\JsonResponse
    {
      
        $data = FormStackUrl::orderByDESC('id');
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
      
        if(isset($request->per_page)){
            $per_page = $request->per_page; 
        }else {
            $per_page = '10';
        }
        $data = $data->paginate($per_page);
        if(empty($data)){
            return $this->jsonResponseFail(trans('common.no_record_found'),401);  
        }
        return $this->jsonResponseSuccess(['user'=> FormStackUrlResource::collection($data)->response()->getData(true)]);
    }
    public function show(Request $request,$id): \Illuminate\Http\JsonResponse
    {
      
        $user = FormStackUrl::find($id);
        $roles = Role::WhereNot('name','Super Admin')->pluck('name');
        if(!$user){
            return $this->jsonResponseFail(trans('common.no_record_found'),401);
        }
    
        return $this->jsonResponseSuccess(['user'=>new  FormStackUrlResource($user),'roles'=>$roles]);
    }

    public function store(FormStackUrlRequest $request) : \Illuminate\Http\JsonResponse{
     
        $data = $request->validated();
      
        $user = FormStackUrl::create($data);

        //$user->assignRole($data['role']);
        if(!empty($user)){
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
    public function update(FormStackUrlRequest $request,$id) : \Illuminate\Http\JsonResponse
    {
     
        $data = $request->validated();
    
        $user = FormStackUrl::find($id);
       
        if(!empty($user)){
            $user->update($data);
            return $this->jsonResponseSuccess(
              ['user'=> new FormStackUrlResource($user)]
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
      
        $user = FormStackUrl::find($id);
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
        $user = FormStackUrl::find($id);
        if(!$user)
        {
            return $this->jsonResponseFail(trans('common.no_record_found'),401);
        }
        $user->status = $data['status'];
        $user->save();
     
        return $this->jsonResponseSuccess(trans('common.status_updated'));
    }
}
