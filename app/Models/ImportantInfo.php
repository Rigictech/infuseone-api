<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportantInfo extends Model
{
     protected $table = "important_info";
     protected $fillable = [
        'content',
       
    ];
}
