<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Response\ResponseTrait;
use App\Http\Requests\Admin\WebsiteUrl\WebsiteUrlRequest;
use App\Http\Resources\Admin\WebsiteUrl\WebsiteUrlResource;
use App\Models\WebsiteUrl;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class WebsiteUrlController extends Controller
{
    use ResponseTrait;
    public function showall(Request $request): \Illuminate\Http\JsonResponse
    {
      
        $data = WebsiteUrl::orderByDESC('id');
        if(isset($request->search)){
            $data = $data->where('title','like',$request->search.'%')
                         ->Orwhere('url','like',$request->search.'%');
                      
        }
        if(isset($request->status)){
            $data = $data->where('status',$request->status);
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
        return $this->jsonResponseSuccess(['website_url'=> WebsiteUrlResource::collection($data)->response()->getData(true)]);
    }
    public function show(Request $request,$id): \Illuminate\Http\JsonResponse
    {
      
        $website_url = WebsiteUrl::find($id);
        $roles = Role::WhereNot('name','Super Admin')->pluck('name');
        if(!$website_url){
            return $this->jsonResponseFail(trans('common.no_record_found'),401);
        }
    
        return $this->jsonResponseSuccess(['website_url'=>new  WebsiteUrlResource($website_url),'roles'=>$roles]);
    }

    public function store(WebsiteUrlRequest $request) : \Illuminate\Http\JsonResponse{
     
        $data = $request->validated();
      
        $website_url = WebsiteUrl::create($data);

        //$website_url->assignRole($data['role']);
        if(!empty($website_url)){
            return $this->jsonResponseSuccess(
                $website_url
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
    public function update(WebsiteUrlRequest $request,$id) : \Illuminate\Http\JsonResponse
    {
     
        $data = $request->validated();
    
        $website_url = WebsiteUrl::find($id);
       
        if(!empty($website_url)){
            $website_url->update($data);
            return $this->jsonResponseSuccess(
              ['website_url'=> new WebsiteUrlResource($website_url)]
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
      
        $website_url = WebsiteUrl::find($id);
        if($website_url){
           
            $website_url->delete();
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
        $website_url = WebsiteUrl::find($id);
        if(!$website_url)
        {
            return $this->jsonResponseFail(trans('common.no_record_found'),401);
        }
        $website_url->status = $data['status'];
        $website_url->save();
     
        return $this->jsonResponseSuccess(trans('common.status_updated'));
    }
}
