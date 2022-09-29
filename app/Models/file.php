<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class file extends Eloquent
{
   use HasFactory;
    protected $collection = 'files';
    protected $fillable = [
        'filenames'
    ];
  
    public function setFilenamesAttribute($value)
    {
        $this->attributes['filenames'] = $value;
    }
}
