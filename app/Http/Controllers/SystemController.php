<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Storage;
use App\Jobs\ProcessNewNewsletter;
use App\Mail\Newsletter;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;


class SystemController extends BaseController
{

    public function sendNewsletter($subject,$type="closing"){

        $users = array();
        if($type == "mid"){
            $users = \App\Models\User::where("mid_session",1)->get();
        } elseif($type == "closing"){
            $users = \App\Models\User::where("closing_session",1)->get();
        }

        // $users = \App\Models\User::where("closing_session",1)->where("email","luisczul@gmail.com")->get();

        foreach($users as $user){

            $output = $user;
            $id = $user->id;

            $instruments = \App\Models\Watchlist::where("user_id",$user->id)->count();

            if($instruments > 0){
                Mail::to($user)->send(new Newsletter($user->id,$subject));
            }

        }

    }







}
