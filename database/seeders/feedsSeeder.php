<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Faker\Factory as Faker;

class feedsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $faker =Faker::create();
        foreach(range(1,100) as $value){
DB::table('feeds')->insert([
            // 'userName' => Str::random(10),
            // 'email' => Str::random(10).'@mibook.in',
            // 'password' => Hash::make('password'),
            'userId' => $faker->randomDigitNotNull,
            'feedId' => $faker->unique()->numberBetween($min = 1, $max = 1000),
            'userName' => $faker->userName,
            'email' => $faker->userName.'@mibook.in',
            'title'=>$faker->text($maxNbChars = 200) ,
            'description'=>$faker->text($maxNbChars = 20000) ,
            //'password' => $faker->password,
            //'create_date' => $faker->,
            'CreatedAt' => $faker->dateTimeThisMonth()->format('Y-m-d H:i:s'),
            'UpdatedAt' => $faker->dateTimeThisMonth()->format('Y-m-d H:i:s'),
 //           'profile_pic'=>$faker->imageUrl($width = 640, $height = 480),
//             'header'=>[
//                 'color'=>$faker->hexcolor,
//                 'iconColor'=>$faker->hexcolor,
//  //               'position'=>'top'
//             ],
            'is_login'=>$faker->boolean,
            'ip_login'=>$faker->ipv4,
            'uploadImage'=>[],
            'likes'=>$faker->randomDigitNotNull,



            
        ]);
        }
        
    }
}
