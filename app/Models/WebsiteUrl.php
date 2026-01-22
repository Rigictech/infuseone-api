<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteUrl extends Model
{
    protected $table = "website_urls";
    protected $fillable = [
        'title',
        'URL',
    ];
}
