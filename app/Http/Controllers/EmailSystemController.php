<?php

namespace App\Http\Controllers;
use App\Models\EmailSystem;
use Illuminate\Http\Request;

class EmailSystemController extends Controller
{
    //
    public function getAllEMessages(){
        $mailMSGs = EmailSystem::get();
        return $mailMSGs;
    }
}
