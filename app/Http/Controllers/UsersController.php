<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function registration(Request $request)
    {
        $allData = $request->all();
        $allData['password']=bcrypt($allData['password']);
        $user = User::create($allData);
        $resArr = [];
        $resArr['token']= $user->createToken('api-application')->accessToken;
        $resArr['name']= $user->userName;
        return response()->json($resArr,200);
            }
        public function login(Request $request){
        if(Auth::attempt([
            'email'=>$request->email,
            'password'=>$request->password
        ])){
            $user = Auth::user();
            $resArr = [];
            $resArr['token']= $user->createToken('api-application')->accessToken;
            $resArr['userName']= $user->userName;
            $resArr['userId']= $user->userId;
            $resArr['headers']= $user->header;
            $resArr['mibemail']= $user->email;
            return response()->json($resArr,200);
            //return $resArr;
        }else{
            return response()->json(['error'=>'Unauthorized Access'],203);
        }
    }
    //
    public function logout(Request $request){
        $token = $request->user()->token();
        $token->revoke();
        $response = ["message"=>"You have successfully logout!!"];
        return response()->json($response,200);
    }
    public function getAll(){
         $UserDate = User::offset(1)->limit('100')->get();
        return response()->json(['status' => 'Success','code'=>200,'data' => $UserDate]);

    }
    public function getUser($userId){
        //return $userId;
         $UserDate = User::where('userId',(int)$userId)->first();
         //return $UserDate;
        // return response()->json(['status' => 'Success','code'=>200,'responseData' => $UserDate]);
        return $UserDate;
    }
    public function store(Request $request){
      //  request()->validate(['userName'=>'required','email'=>'required|email','password'=>'required']);
        //print_r($request->all());exit();
         //Users:create($request->all());
        $id= (User::orderBy('userId', 'desc')->first()->userId)+1;
        $user=new User();
        $user->userId = $id;
        $user->userName = $request->get('username');
        $user->email = $request->get('username').'@mibook.in';
        $user->password = bcrypt($request->get('password'));
        $user->firstname =  $request->get('firstname');
        $user->lastname =  $request->get('lastname');
        $user->DOB =  $request->get('dateofbirth');
        $user->G =  $request->get('gender');
        $user->mobile =  $request->get('mobile');
        $user->font = ['family'=>'DancingScript','style'=>'normal','weight'=>500,'size'=>12];
        $user->header=[
                'color'=>"red",
                'iconColor'=>"white",
                'position'=>'top'
        ];
        $user->is_login=false;
        $user->ip_login=$id;
            // $user->CreatedAt = $faker->dateTimeThisMonth()->format('Y-m-d H:i:s'),
            // 'UpdatedAt' => $faker->dateTimeThisMonth()->format('Y-m-d H:i:s'),
        $user->save();
         return true;
    }
    public function changeUserHeaderColor(Request $request){
        User::where('userId',(int)$request['userId'])->update(array("header.color"=>$request['headerColor'],"header.iconColor"=>$request["iconsColor"]));
        return true;
    }
    // public function getFriendDetails(Request $request){
    //     //User::where('')
    // }
    public function getAllUsers(){
        $users = User::where("is_login",true)->get(["userId"]);
        return response()->json(['status' => 'Success','code'=>200,'data' => $users]);

    }
}
