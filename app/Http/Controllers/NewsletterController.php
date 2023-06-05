<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessNewNewsletter;
use App\Mail\NewsletterShare;
use Illuminate\Http\Request;
use Storage;
use File;
use Carbon;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class NewsletterController extends Controller
{
    public $page_size = 10;
    //

    public function share(Request $request)
    {

        //SAME
        $user = null;
        if (!auth()->check()) {
            return $this->wrong_permissions();
        } else {
            $user = auth()->user();
        }
        //------

        $object = \App\Models\Headline::where("id",$request->news["id"])->first();

        if ($object) {

            $share = new \App\Models\Share;
            $share->user_id = $user->id;
            $share->email = $request->email;
            $share->headline_id = $object->id;
            $share->save();


            $subject = $user->first_name." has shared this with you: ".$object->text;
            Mail::to($request->email)->queue(new NewsletterShare($user->id,$subject,$object->text,$object->content,$object->subtopic,$object->link));

            //SAME
            return response()->json([
                'data' => [
                    'message' => 'The news has been shared',
                    'status' => 'success',
                    'response' => "",
                ]
            ], 200);
            //------
        }
    }





    public function get_news(Request $request)
    {

        // DB::enableQueryLog();
        $user = Auth::loginUsingId(30);

        $objects = null;


        $objects = null;
        $instruments = null;

        $services_that_require_context = array(
            "NFTs" => "NFTs",
            "DeFi" => "DeFi"
        );

        $services_that_require_instruments = array(
            "Bitcoin" => "Bitcoin",
            "Ethereum" => "Ethereum",
            "Bitcoin Price News" => "Bitcoin",
            "Ethereum Price News" => "Ethereum"
        );


        $services_special_logic = array(
            "Altcoin" => "",
        );


        $sources =  \App\Models\SourceService::select("source_id")->where("service",$request->service)->get();

        // print_r(DB::getQueryLog());

        $objects = \App\Models\Headline::select("headlines.*","sources.logo")->where("headlines.source", "rss")->whereNotNull("image_path")
                    ->leftJoin("sources","author_id","sources.id")->whereIn("author_id",$sources);

        if(array_key_exists($request->service,$services_that_require_instruments)){

            $objects = \App\Models\Headline::select("headlines.*","sources.logo")->where("headlines.source", "rss")->whereNotNull("image_path")
                    ->leftJoin("sources","author_id","sources.id");


            $instruments = array();
            if($services_that_require_instruments[$request->service]== "Bitcoin"){
                $instruments = \App\Models\InstrumentGroup::select("id")->where("name","BTC")->first();
                $instruments = array($instruments->id);
            }

            if($services_that_require_instruments[$request->service]== "Ethereum"){
                $instruments = \App\Models\InstrumentGroup::select("id")->where("name","ETH")->first();
                $instruments = array($instruments->id);
            }

            if ($instruments) {
                $filt = array();
                if (is_array($instruments)) {
                    $filt = $instruments;
                } else {
                    $filt = explode(",", $instruments);
                }

                if (is_array($filt) and count($filt) > 0) {

                    $objects = $objects->where(function ($query) use ($filt) {
                        foreach ($filt as $type) {
                            if ($type == "") {
                                $query->orWhereRaw('FIND_IN_SET(0,affects)');
                            } else {
                                $query->orWhereRaw('FIND_IN_SET(' . $type . ',affects)');
                            }
                        }
                    });
                }
            }
        }

        if(array_key_exists($request->service,$services_that_require_context)){
            $objects->leftJoin("headline_contexts","headline_id","headlines.id")->where("headline_contexts.context",$request->service);
        }

        if(array_key_exists($request->service,$services_special_logic)){
            if($request->service == "Altcoin"){
                $objects->whereNotNull("affects");
                $objects->where("affects","!=","");
                $objects = $objects->where(function ($query) {
                    $query->WhereRaw('FIND_IN_SET(1,affects) = 0');
                    $query->WhereRaw('FIND_IN_SET(3,affects) = 0');
                });
            }

        }

        if($request->pages > 0){
            $pages = $request->pages*$this->page_size;
        }

        $objects = $objects->orderBy("posted_at", "DESC")->orderBy("created_at", "DESC")->take($pages)->get();

        // print_r(DB::getQueryLog());


        if ($objects) {

            $output = $objects;

            //SAME
            return response()->json([
                'data' => [
                    'message' => '',
                    'status' => 'success',
                    'response' => $output,
                ]
            ], 200);
            //------
        } else {

            //SAME
            return response()->json([
                'data' => [
                    'message' => "Can't find news",
                    'status' => 'error',
                    'response' => "",
                ]
            ], 200);
            //------
        }
    }

    public function get_news_stocks(Request $request)
    {

        DB::enableQueryLog();
        $user = Auth::loginUsingId(30);
        logger()->erro($user);
        //SAME
        // $user = null;
        // if (!auth()->check()) {
        //     return $this->wrong_permissions();
        // } else {
        //     $user = auth()->user();
        // }

        $objects = null;

        $objects = \App\Models\Headline::select("headlines.*","sources.logo")->leftJoin("sources","author_id","sources.id")->orderBy("created_at", "DESC")->orderBy("posted_at", "DESC")->take($this->page_size);

        $instru = \App\Models\InstrumentGroup::where("name",$request->instrument)->first();

        if($instru){

        $instruments = array($instru->id);

        if ($instruments) {
            $filt = array();
            if (is_array($instruments)) {
                $filt = $instruments;
            } else {
                $filt = explode(",", $instruments);
            }

            if (is_array($filt) and count($filt) > 0) {

                $objects = $objects->where(function ($query) use ($filt) {
                    foreach ($filt as $type) {
                        if ($type == "") {
                            $query->orWhereRaw('FIND_IN_SET(0,affects)');
                        } else {
                            $query->orWhereRaw('FIND_IN_SET(' . $type . ',affects)');
                        }
                    }
                });
            }
        }
        // if($watchlist){
        //     $objects->where("headlines.relevance",">=",$watchlist->filter);
        // }

        $objects = $objects->where("reject",0)->get();


        // logger()->error(DB::getQueryLog());


        return response()->json([
            'data' => [
                'message' => '',
                'status' => 'success',
                'response' => $objects,
            ]
        ], 200);

    } else {

        return response()->json([
            'data' => [
                'message' => '',
                'status' => 'success',
                'response' => array(),
            ]
        ], 200);

    }

    }
}
