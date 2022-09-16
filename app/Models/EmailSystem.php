<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class EmailSystem extends Model
{
    protected $collection = 'EmailMessages';
    use HasFactory;
}
