<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\defaultSettings;

class defaultSettingsController extends Controller
{
    //
public function getSettings(){
    $totalSettings = defaultSettings::get();
    return response()->json(['status' => 'Success','code'=>200,'data' => $totalSettings]);
}

}
