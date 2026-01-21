<?php

namespace App\Traits;

trait CommonTrait
{
    public function saveProfileImage($data){
      $base64String = $data;
      if (preg_match('/^data:image\/(\w+);base64,/', $base64String, $type)) 
      {
        $base64String = substr($base64String, strpos($base64String, ',') + 1);
        $type = strtolower($type[1]); // jpg, png, gif, etc.
        $imageData = base64_decode($base64String);
        if ($imageData === false) {
          return $this->jsonResponseFail(trans('profile.decoding_failed'));   
        }
        $filename = uniqid() . '.' . $type;
        $filePath = 'profile/' . $filename;
        Storage::disk('public_uploads')->put($filePath, $imageData);
        return $filePath;
      }else {
        return false;
      }
    }
}
