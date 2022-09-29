<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class EmailSystem extends Eloquent
{
    protected $collection = 'EmailMessages';
    use HasFactory;
}
