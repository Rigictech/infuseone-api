<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UploadPDF extends Model
{
    protected $table = "upload_pdf";
    protected $fillable = [
        'title',
        'pdf',
    ];
}
