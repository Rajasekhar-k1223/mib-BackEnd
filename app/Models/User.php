<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    protected $collection = 'users';
    use HasApiTokens,HasFactory;
     protected $fillable = [
         'userName',
         'email',
         'password',
     ];
    public function run()
{
    User::factory()
            ->count(50)
            ->hasPosts(1)
            ->create();
}
}
