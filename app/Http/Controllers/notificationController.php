<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FriendRequest;
use AApp\Models\notification;
class notificationController extends Controller
{
    //
    public function get(Request $request){
        $noti = notification::where("to",(int)$request->id)->get();
        $notiAll = notification::where("public",true)->get();
        $totalNoti = array_merge($noti,$notiAll);
        return $totalNoti;

    }
}
