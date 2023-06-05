<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use File;


class PriceController extends Controller
{
    public $page_size = 10;
    //
    public function crypto_prices()
    {

        //SAME
        $user = null;
        if (!auth()->check()) {
            return $this->wrong_permissions();
        } else {
            $user = auth()->user();
        }
        //------



        $objects = \App\Models\InstrumentGroup::select("name","last_price","last_24_change","marketcap","last_price_date","year_start")->where("include",1)->where("chapter","general")->orderBy("marketcap", "DESC")->get();
        $first = true;
        foreach ($objects as $object){
            if($first){
                $output["general_time"] = $object->last_price_date;
                break;
            }
            $first = false;
        }
        $output["general"] = $objects;


        $objects = \App\Models\InstrumentGroup::select("name","last_price","last_24_change","marketcap","last_price_date","year_start")->where("include",1)->where("chapter","deep")->orderBy("marketcap", "DESC")->get();
        $first = true;
        foreach ($objects as $object){
            if($first){
                $output["deep_time"] = $object->last_price_date;
                break;
            }
            $first = false;
        }
        $output["deep"] = $objects;

        //SAME
        return response()->json([
            'data' => [
                'message' => '',
                'status' => 'success',
                'response' => $output,
            ]
        ], 200);
        //------
    }

    public function btc_eth()
    {

        //SAME
        $user = null;
        if (!auth()->check()) {
            return $this->wrong_permissions();
        } else {
            $user = auth()->user();
        }
        //------



        $objects = \App\Models\InstrumentGroup::select("name","last_price","last_24_change","marketcap","last_price_date","year_start")->where("name","BTC")->first();
        $output["BTC"] = $objects->last_price;
        $output["BTC_change"] = $objects->last_24_change;
        $output["BTC_change_year"] = ($objects->last_price-$objects->year_start)/$objects->year_start;

        $objects = \App\Models\InstrumentGroup::select("name","last_price","last_24_change","marketcap","last_price_date")->where("name","ETH")->first();
        $output["ETH"] = $objects->last_price;
        $output["ETH_change"] = $objects->last_24_change;


        //SAME
        return response()->json([
            'data' => [
                'message' => '',
                'status' => 'success',
                'response' => $output,
            ]
        ], 200);
        //------
    }


}
