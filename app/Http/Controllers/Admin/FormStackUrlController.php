<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Response\ResponseTrait;
use App\Http\Requests\Admin\FormStackUrl\FormStackUrlRequest;
use App\Http\Resources\Admin\FormStackUrl\FormStackUrlResource;
use App\Models\FormStackUrl;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class FormStackUrlController extends Controller
{
    use ResponseTrait;
    public function showall(Request $request): \Illuminate\Http\JsonResponse
    {
      
        $data = FormStackUrl::orderByDESC('id');
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
        return $this->jsonResponseSuccess(['form_stack_url'=> FormStackUrlResource::collection($data)->response()->getData(true)]);
    }
    public function show(Request $request,$id): \Illuminate\Http\JsonResponse
    {
      
        $form_stack_url = FormStackUrl::find($id);
       
        if(!$form_stack_url){
            return $this->jsonResponseFail(trans('common.no_record_found'),401);
        }
    
        return $this->jsonResponseSuccess(['form_stack_url'=>new  FormStackUrlResource($form_stack_url)]);
    }

    public function store(FormStackUrlRequest $request) : \Illuminate\Http\JsonResponse{
     
        $data = $request->validated();
      
        $form_stack_url = FormStackUrl::create($data);

        if(!empty($form_stack_url)){
            return $this->jsonResponseSuccess(
                $form_stack_url
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
    
        $form_stack_url = FormStackUrl::find($id);
       
        if(!empty($form_stack_url)){
            $form_stack_url->update($data);
            return $this->jsonResponseSuccess(
              ['form_stack_url'=> new FormStackUrlResource($form_stack_url)]
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
      
        $form_stack_url = FormStackUrl::find($id);
        if($form_stack_url){
           
            $form_stack_url->delete();
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
        $form_stack_url = FormStackUrl::find($id);
        if(!$form_stack_url)
        {
            return $this->jsonResponseFail(trans('common.no_record_found'),401);
        }
        $form_stack_url->status = $data['status'];
        $form_stack_url->save();
     
        return $this->jsonResponseSuccess(trans('common.status_updated'));
    }
}
