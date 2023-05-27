<?php

namespace App\Http\Controllers;
use App\Models\FriendsModel;
use App\Models\User;
use App\Models\messages;
use App\Models\FriendRequest;
use Illuminate\Http\Request;

class FriendsController extends Controller
{
    //
 
    public function GetMessagesFromFriends(Request $request){
        $message1 = messages::where("from",(int)$request->from)->where("to",(int)$request->to)->get();
        $message2 = messages::Where("from",(int)$request->to)->where("to",(int)$request->from)->get();
        //$message = sort(array_merge($message1->toArray(),$message2->toArray()));
        $merge = $message1->merge($message2)->sortBy('created_at');
       //$merge = collect(array_merge($message1,$message2))->sortBy('created_at');
        $message = $merge->values()->all();
        return response()->json(['status' => 'Success','code'=>200,'data' => $message]);
    }
    public function SendMessageToFriend(Request $request){
       // $messageId = messages::find() -> sort(array('messageId' => -1)) -> limit(1); 
      $messageId= messages::orderBy('messageId', 'desc')->first();
      $msg = $messageId ? 1:0;
      if($msg ==1){
        $messageId = messages::orderBy('messageId', 'desc')->first()->messageId;
      }else{
        $messageId=0;
      }
      //return $messageId;
        $from = $request->get("from");
        $to = $request->get("to");
        $message = $request->get("message");
        $SendMesssge = new messages();
        $SendMesssge->messageId= $messageId+1;
        $SendMesssge->from = (int)$from;
        $SendMesssge->to = (int)$to;
        $SendMesssge->message = $message;
        // $SendMesssge->created_at = new DateTime();
        $SendMesssge->save();
       // $success = event(new App\Events\NewMessage($SendMesssge));
        return response()->json(['status' => 'Success','code'=>200,'data' => $SendMesssge]);
}
        public function getFriendsList(Request $request)
        {
        $message1 = User::where("userId",(int)$request->from)->get(["friends_list"]);
        return response()->json(['status' => 'Success','code'=>200,'data' => $message1]);
        }
        public function getFriendDetails(Request $request)
        {
        $message1 = User::where("userId",(int)$request->from)->get();
        return response()->json(['status' => 'Success','code'=>200,'data' => $message1]);
        }
        public function findFriendOrPage(Request $request){
                 $message1 = User::where('email', 'like', '%'.$request->searchName.'%')->orwhere('userName', 'like', '%'.$request->searchName.'%')->get();
        return response()->json(['status' => 'Success','code'=>200,'data' => $message1]);
        }
        public function FriendRequestFrom(Request $request){
                 //$message1 = User::where('email', 'like', '%'.$request->searchName.'%')->orwhere('userName', 'like', '%'.$request->searchName.'%')->get();
                  $FriendRequest= new FriendRequest();
                  $FriendRequest->from = $request->get("from");
        $FriendRequest->to = $request->get("to");
        $FriendRequest->status = $request->get("status");
        $FriendRequest->save();
        return response()->json(['status' => 'Success','code'=>200,'data' => $FriendRequest]);
        }
        public function FriendRequestFromCheck(Request $request){
                 $message1 = FriendRequest::where("from",(int)$request->from)->where("to",(int)$request->to)->get();
        return response()->json(['status' => 'Success','code'=>200,'data' => $message1]);
        }
        public function CheckListNotification(Request $request){
                 $message1 = FriendRequest::where("to",(int)$request->from)->where("status","!=",$request->status)->get()->toarray();
               // return $message1;
               $allNotiList = [];
               foreach($message1 as $msg){
                $frdDe = User::select("userId","userName","profile_pic")->where('userId',(int)$msg['from'])->get();
                $allNotiList[] = $frdDe;
               }
//               $output = array_map(function($element) {
//     return (object) $element;
// }, $allNotiList);
              //  $arrayOfObjects = $allNotiList.map(function(item){return { item}});
               //return $message1;
//                $arrayOfObjects = array_map(function ($array) {
//     return json_decode(json_encode($array), false);
// }, $allNotiList);
        return response()->json(['status' => 'Success','code'=>200,'data' => $allNotiList]);
        }
        public function friendRequestAcceptance(Request $request){
          $updateRequest  = FriendRequest::where('to',(int)$request->from)->where('from',(int)$request->to)->update(["status"=>$request->status]);
          $fromID = User::select("userId","userName")->where('userId',(int)$request->to)->get()->toarray();
          $toID = User::select("userId","userName")->where('userId',(int)$request->from)->get()->toarray();
          // $data = array("2"=>array("name":"nikhil"));
          $requestingfrom = User::where('userId',(int)$request->to);//return mongo data
          $requestingfrom->push('friends_list',$fromID);
          $requestingto = User::where('userId',(int)$request->from);//return mongo data
          $requestingto->push('friends_list',$toID);

          return response()->json(['status' => 'Success','code'=>200,'data' => $updateRequest]);
        }
       
                
}