<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Models\feeds;
use App\Models\Likes;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
//use App\Http\Controllers\VideoStream;
use App\Http\Helpers\VideoStream;
//use App\Models\File;
// use Illuminate\Support\Facades\Storage;
class feedsController extends Controller
{
    //
    public function getAll(Request $request){
        if(Cache::has('feedsData')){
            $getFeeds= Cache::get('feedsData');
            return response()->json(['status' => 'Success','code'=>200,'data' => $getFeeds]);
        }else{
            $getFeeds = Cache::remember("feedsData",600,function()use($request){
            return feeds::offset($request->page)->limit($request->SetLimit)->orderBy('feedId','DESC')->get();
        });
        return response()->json(['status' => 'Success','code'=>200,'data' => $getFeeds]);
        }

         //$UserDate = feeds::offset($request->page)->limit($request->SetLimit)->orderBy('feedId','DESC')->get();
       //  exec('D:\mibook\mib\mib-api\python_file\mail.py');
        //return response()->json(['status' => 'Success','code'=>200,'data' => $getFeeds]);

    }
    public function getAllFeeds(Request $request){

         $UserDate = feeds::offset($request->page)->limit($request->SetLimit)->orderBy('feedId','DESC')->get();
       //  exec('D:\mibook\mib\mib-api\python_file\mail.py');
        return response()->json(['status' => 'Success','code'=>200,'data' => $UserDate]);

    }
    public function createFeed(Request $request){
         $files = [];
         //print_r($request->hasfile('filenames'));

        if($request->file('uploadImage'))
         {

            foreach($request->file('uploadImage') as $file)
            {
                // return $file;
                $name = time().rand(1,100000000);
                $storename = $name.'.'.$file->extension();
                //$name = md5($file->getClientOriginalName());
                $extension = $file->extension();
                $type = $file->getMimeType();
                $folder = null; $filename = null;
                list($width, $height) = getimagesize($file);
               // $path = $file->storeAs('images',$storename,'public');
                $name = !is_null($filename) ? $filename : Str::random(25);

                $path =  $file->storeAs(
                    $folder,
                    $name . "." . $file->getClientOriginalExtension(),
                    'gcs'
                );
                //$img = file_get_contents($file->move(public_path('files'), $name));
                //$data = "data:@file/" . $file->extension() . ";base64,".base64_encode($img);
                //$data = 'data:'.$type.';base64,'.base64_encode($img);
               // $storagepath =  env('APP_URL').':8000/storage/images';
               # If you set S3 as your default:
// $contents = Storage::get('path/to/file.ext');
// Storage::put('path/to/file.ext', 'some-content');

// # If you do not have S3 as your default:
// $contents = Storage::disk('s3')->get('path/to/file.ext');
// Storage::disk('s3')->put('path/to/file.ext', 'some-content');
        //          $path = Storage::disk('s3')->put('images',$file);
        // $path = Storage::disk('s3')->url($path);
       //$path = $request->file('uploadImage')->store('images', 's3');
       //$path = Storage::disk('s3')->put('images',$file);
                //$getPath = Storage::disk('gcs')->url($path);
                $getPath = 'files.mibook.in/'.$path;
                $newItem = array('uri'=>$name,'type'=>$type,'extension'=>$extension,'path'=>$getPath,'width'=>$width,'height'=>$height);
                 array_push($files,$newItem);
            }
         }
  $newId = feeds::orderBy('feedId', 'desc')->first()->feedId;

        $file= new feeds();
        // $file->filenames = $files;
        $file->feedId = $newId+1;
        $file->userId = $request->get('userId');
        $file->description = $request->get('description');
        $file->likes = 0;
        $file->emojiAction = "default";
        $file->LikeAction=false;
        $file->userName = $request->get('userName');
        $file->postType=false;
        $file->Share=false;
        $file->users = [];
        $file->uploadImage= $files;
        // $file->CreatedAt = new Date();
        // $file->UpdatedAt = new Date();
        $file->email=$request->get('email');
        $file->is_login =false;
        $file->ipconfig=$request->get('ipaddress');

         $file->save();
  return  $file;
        //return $file;
      //  $name = $request->file('uploadImages');
      //  $size = count(collect($request));
    //   $files = $request->file('uploadImages');
    // $errors = [];
    // return $files;
//        foreach ($files as $file) {

//         $extension = $file->getClientOriginalExtension();
//  return $extension;
        // $check = in_array($extension,$allowedfileExtension);

        // if($check) {
        //     foreach($request->fileName as $mediaFiles) {

        //         $path = $mediaFiles->store('public/images');
        //         $name = $mediaFiles->getClientOriginalName();

        //         //store image file into directory and db
        //         $save = new Image();
        //         $save->title = $name;
        //         $save->path = $path;
        //         $save->save();
        //     }
        // } else {
        //     return response()->json(['invalid_file_format'], 422);
        // }

        //return response()->json(['file_uploaded'], 200);

    // }

        //return $size;
         //$UserDate = feeds::offset($request->page)->limit($request->SetLimit)->get();

        //return response()->json(['status' => 'Success','code'=>200,'data' => $UserDate]);

    }
    public function CheckdisLikes(Request $request){

        // $Like = new Likes();
        // $Like->feedId = $request->get('feedId');
        // $Like->userID = $request->get('UserID');
        // $Like->delete();
        likes::where('feedId','=',$request->get('feedId'))->where('userID','=',(int)$request->get('UserID'))->delete();
        $likesUsers = [];
        $likes = likes::where('feedId',$request->get('feedId'))->get();
        foreach($likes as $like){
            if($like->userID != null){
                array_push($likesUsers,$like->userID);
            }

        }
        //return $likesUsers;
        $userListLoad = array_diff($likesUsers, array($request->get('UserID')));
        $likes = count(likes::where('feedId',$request->get('feedId'))->get());

       // return $likes;
        $data=array(['likes'=>$likes]);
        //return $data;
        //$array = array('apple', 'orange', 'strawberry', 'blueberry', 'kiwi', 'strawberry'); //throw in another 'strawberry' to demonstrate that it removes multiple instances of the string



        $feed = feeds::where('feedId',$request->get('feedId'))->first();
        $feed->likes = $likes;
        $feed->users = $userListLoad;
        $feed->save();
        return response()->json(['status' => 'Success','code'=>200,'data' => $likes]);
    }
    public function CheckLikes(Request $request){

        $Like = new Likes();
        $Like->feedId = $request->get('feedId');
        $Like->userID = (int)$request->get('UserID');
        $Like->save();
        $likesUsers = [];
        $likes = likes::where('feedId',$request->get('feedId'))->get();
        foreach($likes as $like){
            if($like->userID != null){
                array_push($likesUsers,$like->userID);
            }

        }
        //return $likesUsers;
        $likes = count(likes::where('feedId',$request->get('feedId'))->get());

       // return $likes;
        $data=array(['likes'=>$likes]);
        //return $data;
        //$array = array('apple', 'orange', 'strawberry', 'blueberry', 'kiwi', 'strawberry'); //throw in another 'strawberry' to demonstrate that it removes multiple instances of the string
//$userListLoad = array_diff($likes, array($request->get('UserID')));


        $feed = feeds::where('feedId',$request->get('feedId'))->first();
        $feed->likes = $likes;
        $feed->users = $likesUsers;

        $feed->save();

        return response()->json(['status' => 'Success','code'=>200,'data' => $likes]);
    }
    public function getAllLikes(Request $request){
         $UserDate = likes::offset($request->page)->limit($request->SetLimit)->get();
        return response()->json(['status' => 'Success','code'=>200,'data' => $UserDate]);
    }
     public function GetfeedData($feedId){
        //return $userId;
         $UserDate = feeds::where('feedId',(int)$feedId)->first();
         //return $UserDate;
         return response()->json(['status' => 'Success','code'=>200,'responseData' => $UserDate]);
       // return $UserDate;
    }
//  public function stream($filename)
public function emojismileAdd(Request $request){

    feeds::where('feedId',(int)$request->get("feedId"))->update(["emojismileAdd"=>$request->get("emojismileAdd")]);
    return response()->json(['status' => 'Success','code'=>200]);
}
  public function stream(Request $request)
    {

        $filePath = public_path('storage\images\''.$request->VideofileName);
      //  $videosDir      = config('larastreamer.basepath');
        // if (file_exists($filePath = asset('storage/images/164666735316948130.mp4'))) {
         //    return $filePath;
            $stream = new VideoStream($filePath);
            return response()->stream(function() use ($stream) {
                $stream->start();
            });
        // }
        // return response("File doesn't exists", 404);
    }
    public function Emojiaction(){
        return "hello";
    }

}
