<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Response\ResponseTrait;
use App\Http\Requests\Admin\UploadPDF\UploadPDFRequest;
use App\Http\Resources\Admin\UploadPDF\UploadPDFResource;
use App\Models\UploadPDF;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UploadPDFController extends Controller
{
    use ResponseTrait;
    public function showall(Request $request): \Illuminate\Http\JsonResponse
    {
      
        $data = UploadPDF::orderByDESC('id');
        if(isset($request->search)){
            $data = $data->where('title','like',$request->search.'%')
                         ->Orwhere('pdf','like',$request->search.'%');
                      
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
        return $this->jsonResponseSuccess(['upload_pdf'=> UploadPDFResource::collection($data)->response()->getData(true)]);
    }
    public function show(Request $request,$id): \Illuminate\Http\JsonResponse
    {
      
        $upload_pdf = UploadPDF::find($id);
        if(!$upload_pdf){
            return $this->jsonResponseFail(trans('common.no_record_found'),401);
        }
    
        return $this->jsonResponseSuccess(['upload_pdf'=>new  UploadPDFResource($upload_pdf),'roles'=>$roles]);
    }

    public function store(UploadPDFRequest $request) : \Illuminate\Http\JsonResponse{
     
        $data = $request->validated();
      
        $upload_pdf = UploadPDF::create($data);

        if(!empty($upload_pdf)){
            return $this->jsonResponseSuccess(
                $upload_pdf
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
    public function update(UploadPDFRequest $request,$id) : \Illuminate\Http\JsonResponse
    {
     
        $data = $request->validated();
    
        $upload_pdf = UploadPDF::find($id);
       
        if(!empty($upload_pdf)){
            $upload_pdf->update($data);
            return $this->jsonResponseSuccess(
              ['upload_pdf'=> new UploadPDFResource($upload_pdf)]
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
      
        $upload_pdf = UploadPDF::find($id);
        if($upload_pdf){
           
            $upload_pdf->delete();
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
        $upload_pdf = UploadPDF::find($id);
        if(!$upload_pdf)
        {
            return $this->jsonResponseFail(trans('common.no_record_found'),401);
        }
        $upload_pdf->status = $data['status'];
        $upload_pdf->save();
     
        return $this->jsonResponseSuccess(trans('common.status_updated'));
    }
}
