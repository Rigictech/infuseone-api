<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormStackUrl extends Model
{
    protected $table = "form_stack_url";
     protected $fillable = [
        'title',
        'URL',
    ];
}
