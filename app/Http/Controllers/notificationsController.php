<?php

namespace App\Http\Controllers;
use App\Models\notifications;
use Illuminate\Http\Request;

class notificationsController extends Controller
{
    //
    public function getNotifications()
    {
         $noti = notifications::where("userId",(int)$request->from)->get();
        return response()->json(['status' => 'Success','code'=>200,'data' => $noti]);
    }
}
