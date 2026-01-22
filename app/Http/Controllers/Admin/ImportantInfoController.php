<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Response\ResponseTrait;
use App\Http\Requests\Admin\ImportantInfo\ImportantInfoRequest;
use App\Http\Resources\Admin\ImportantInfo\ImportantInfoResource;
use App\Models\ImportantInfo;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class ImportantInfoController extends Controller
{
     use ResponseTrait;
    public function showall(Request $request): \Illuminate\Http\JsonResponse
    {
      
        $data = ImportantInfo::orderByDESC('id');
       
        $data = $data->get();
        if(empty($data)){
            return $this->jsonResponseFail(trans('common.no_record_found'),401);  
        }
        return $this->jsonResponseSuccess(['important_info'=> ImportantInfoResource::collection($data)->response()->getData(true)]);
    }
    public function show(Request $request,$id): \Illuminate\Http\JsonResponse
    {
      
        $important_info = ImportantInfo::find($id);
        if(!$important_info){
            return $this->jsonResponseFail(trans('common.no_record_found'),401);
        }
    
        return $this->jsonResponseSuccess(['important_info'=>new  ImportantInfoResource($important_info),'roles'=>$roles]);
    }

    public function store(ImportantInfoRequest $request) : \Illuminate\Http\JsonResponse{
     
        $data = $request->validated();
      
        $important_info = ImportantInfo::create($data);

        //$important_info->assignRole($data['role']);
        if(!empty($important_info)){
            return $this->jsonResponseSuccess(
                $important_info
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
    public function update(ImportantInfoRequest $request,$id) : \Illuminate\Http\JsonResponse
    {
     
        $data = $request->validated();
    
        $important_info = ImportantInfo::find($id);
       
        if(!empty($important_info)){
            $important_info->update($data);
            return $this->jsonResponseSuccess(
              ['important_info'=> new ImportantInfoResource($important_info)]
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
      
        $important_info = ImportantInfo::find($id);
        if($important_info){
           
            $important_info->delete();
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
        $important_info = ImportantInfo::find($id);
        if(!$important_info)
        {
            return $this->jsonResponseFail(trans('common.no_record_found'),401);
        }
        $important_info->status = $data['status'];
        $important_info->save();
     
        return $this->jsonResponseSuccess(trans('common.status_updated'));
    }
}
