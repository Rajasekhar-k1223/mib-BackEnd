<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class feeds extends Model
{
    protected $collection = 'feeds';
    use HasFactory;
     protected $fillable = [
        'uploadImage'
    ];
    public function setFilenamesAttribute($value)
    {
        $this->attributes['uploadImage'] = $value;
    }
    public function run()
{
    User::factory()
            ->count(50)
            ->hasPosts(1)
            ->create();
}
}
