<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Google\Cloud\Iot\V1\DeviceManagerClient;

class WatchlistController extends Controller
{
    //
    public function list()
    {


        $user = null;
        if (!auth()->check()) {
            return $this->wrong_permissions();
        } else {
            $user = auth()->user();
        }

        $objects = \App\Models\Watchlist::select("watchlists.*", "instrument_groups.logo", "instrument_groups.context_name","watchlists.last_id_read","stocks.last","stocks.change")->leftJoin("instrument_groups", "instrument_group_id", "instrument_groups.id")
        ->leftJoin("stocks", "stocks.ticker", "watchlists.ticker")->orderBy("watchlists.name", "ASC")
        ->where("watchlists.user_id",$user->id);
        $objects = $objects->get();
        $output = $objects;


        return response()->json([
            'data' => [
                'message' => '',
                'status' => 'success',
                'response' => $output,
            ]
        ], 200);
    }

    public function view_watchlist(Request $request)
    {


        $user = null;
        if (!auth()->check()) {
            return $this->wrong_permissions();
        } else {
            $user = auth()->user();
        }

        $objects = \App\Models\Watchlist::select("watchlists.favorite","instrument_groups.name","instrument_groups.logo", "instrument_groups.context_name","instrument_groups.id")->leftJoin("instrument_groups", "instrument_group_id", "instrument_groups.id")
        ->where("watchlists.user_id",$request->id);
        $objects = $objects->get();
        $output = $objects;


        return response()->json([
            'data' => [
                'message' => '',
                'status' => 'success',
                'response' => $output,
            ]
        ], 200);
    }

    public function list_render($user = 0)
    {

        if($user != 0){

            $user = \App\Models\User::find($user);

        } else {
            if (!auth()->check()) {
                return $this->wrong_permissions();
            } else {
                $user = auth()->user();
            }

        }

        $objects = \App\Models\Watchlist::select("watchlists.*", "instrument_groups.logo", "instrument_groups.context_name","watchlists.last_id_read","stocks.*")->leftJoin("instrument_groups", "instrument_group_id", "instrument_groups.id")
                    ->leftJoin("stocks", "stocks.ticker", "watchlists.ticker")
                    ->where("user_id",$user->id)
                    ->where("favorite",0)
                    ->orderBy("stocks.change", "DESC")->get();

        $output = $objects;


        return $output;

    }

    public function list_render_favorites($user = 0)
    {

        if($user != 0){

            $user = \App\Models\User::find($user);

        } else {
            if (!auth()->check()) {
                return $this->wrong_permissions();
            } else {
                $user = auth()->user();
            }

        }

        $objects = \App\Models\Watchlist::select("watchlists.*", "instrument_groups.logo", "instrument_groups.context_name","watchlists.last_id_read","stocks.*")->leftJoin("instrument_groups", "instrument_group_id", "instrument_groups.id")
                    ->leftJoin("stocks", "stocks.ticker", "watchlists.ticker")
                    ->where("user_id",$user->id)
                    ->where("favorite",1)
                    ->orderBy("stocks.change", "DESC")->get();

        $output = $objects;


        return $output;

    }

    public function mark_read(Request $request)
    {


        $user = null;
        if (!auth()->check()) {
            return $this->wrong_permissions();
        } else {
            $user = auth()->user();
        }

        $object = \App\Models\Watchlist::where("user_id",$user->id)->where("ticker",$request->ticker)->first();

        if($object){

            $object->last_id_read = $request->last_id_read;
            $object->save();

        }

        return response()->json([
            'data' => [
                'message' => '',
                'status' => 'success',
                'response' => "",
            ]
        ], 200);
    }

    public function update_price(Request $request)
    {


        $user = null;
        if (!auth()->check()) {
            return $this->wrong_permissions();
        } else {
            $user = auth()->user();
        }

        $object = \App\Models\Watchlist::where("user_id",$user->id)->where("ticker",$request->ticker)->first();

        if($object){

            $object->average_price = $request->price;
            $object->save();

            return response()->json([
                'data' => [
                    'message' => '',
                    'status' => 'success',
                    'response' => "",
                ]
            ], 200);

        } else {

            return response()->json([
                'data' => [
                    'message' => 'You do not have that ticker in your watchlist',
                    'status' => 'error',
                    'response' => "",
                ]
            ], 200);

        }


    }

    public function get_filters_count(Request $request){

        $user = null;
        if (!auth()->check()) {
            return $this->wrong_permissions();
        } else {
            $user = auth()->user();
        }

        $object = \App\Models\Watchlist::where("user_id",$user->id)->where("ticker",$request->ticker)->first();

        $output = array("no"=>0,"some"=>0,"ai"=>0);

        if($object){
            $filt = array($object->instrument_group_id);
            if (is_array($filt) and count($filt) > 0) {
                $sub = \App\Models\Headline::select("id");
                $last_read = $sub->where(function ($query) use ($filt) {
                    foreach ($filt as $type) {
                        if ($type == "") {
                            $query->orWhereRaw('FIND_IN_SET(0,affects)');
                        } else {
                            $query->orWhereRaw('FIND_IN_SET(' . $type . ',affects)');
                        }
                    }
                });
                $output["no"] = $sub->count();
            }

            if (is_array($filt) and count($filt) > 0) {
                $sub = \App\Models\Headline::select("id");
                $last_read = $sub->where(function ($query) use ($filt) {
                    foreach ($filt as $type) {
                        if ($type == "") {
                            $query->orWhereRaw('FIND_IN_SET(0,affects)');
                        } else {
                            $query->orWhereRaw('FIND_IN_SET(' . $type . ',affects)');
                        }
                    }
                });
                $sub->where("relevance",">=",50);
                $output["some"] = $sub->count();
            }

            if (is_array($filt) and count($filt) > 0) {
                $sub = \App\Models\Headline::select("id");
                $last_read = $sub->where(function ($query) use ($filt) {
                    foreach ($filt as $type) {
                        if ($type == "") {
                            $query->orWhereRaw('FIND_IN_SET(0,affects)');
                        } else {
                            $query->orWhereRaw('FIND_IN_SET(' . $type . ',affects)');
                        }
                    }
                });
                $sub->where("relevance",">=",75);
                $output["ai"] = $sub->count();
            }
            // if($object->last_id_read != ""){
            //     $output["ai"] = \App\Models\Headline::where("id",">",$object->last_id_read)->where("relevance",">=",75)->count();
            // } else {
            //     $output["ai"] = \App\Models\Headline::where("id",">",$object->last_id_read)->where("relevance",">=",75)->count();
            // }

        }

        return response()->json([
            'data' => [
                'message' => '',
                'status' => 'success',
                'response' => $output,
            ]
        ], 200);

    }

    public function update_filter(Request $request)
    {

        $user = null;
        if (!auth()->check()) {
            return $this->wrong_permissions();
        } else {
            $user = auth()->user();
        }

        $object = \App\Models\Watchlist::where("user_id",$user->id)->where("ticker",$request->ticker)->first();

        if($object){

            $object->filter = $request->filter;
            $object->save();

        }

        return response()->json([
            'data' => [
                'message' => '',
                'status' => 'success',
                'response' => "",
            ]
        ], 200);
    }

    public function mark_all_read(Request $request)
    {


        $user = null;
        if (!auth()->check()) {
            return $this->wrong_permissions();
        } else {
            $user = auth()->user();
        }

        $objects = \App\Models\Watchlist::where("user_id",$user->id)->get();

        foreach($objects as $object){

            $last_read = \App\Models\Headline::orderby("id","DESC");
            $filt = array($object->instrument_group_id);
            if (is_array($filt) and count($filt) > 0) {

                $last_read = $last_read->where(function ($query) use ($filt) {
                    foreach ($filt as $type) {
                        if ($type == "") {
                            $query->orWhereRaw('FIND_IN_SET(0,affects)');
                        } else {
                            $query->orWhereRaw('FIND_IN_SET(' . $type . ',affects)');
                        }
                    }
                });
            }
            $last_read = $last_read->orderBy("id","DESC")->first();
            if($last_read){

                $object->last_id_read = $last_read->id;
                $object->save();

            }
        }



        return response()->json([
            'data' => [
                'message' => '',
                'status' => 'success',
                'response' => "",
            ]
        ], 200);
    }

    public function unread(Request $request)
    {

        $output = array();

        $user = Auth::loginUsingId(30);
        // auth()->user()->id;

        $tickers = \App\Models\Watchlist::where("user_id", $user->id)->get();

        foreach($tickers as $ticker){

        if ($ticker) {

            $instru = \App\Models\InstrumentGroup::find($ticker->instrument_group_id);

            if ($instru) {

                if ($ticker->last_id_read != "" and is_int($ticker->last_id_read)) {
                    $objects = \App\Models\Headline::where("id", ">", $ticker->last_id_read);

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
                    $objects->where("relevance",">=",$ticker->filter);

                    $output[$ticker->ticker] = $objects->count();


                } else {

                    $instruments = array($instru->id);
                    $objects = null;
                    if ($instruments) {
                        $filt = array();
                        if (is_array($instruments)) {
                            $filt = $instruments;
                        } else {
                            $filt = explode(",", $instruments);
                        }

                        if (is_array($filt) and count($filt) > 0) {

                            $objects = \App\Models\Headline::where(function ($query) use ($filt) {
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
                    $objects->where("relevance",">=",$ticker->filter);
                    if($ticker->last_id_read == 0 || $ticker->last_id_read == ""){
                        $objects->where("created_at",">=",Carbon::now()->subDays(7));
                    }
                    $output[$ticker->ticker] = $objects->count();

                }
            }
        }
    }

    return response()->json([
        'data' => [
            'message' => '',
            'status' => 'success',
            'response' => $output,
        ]
    ], 200);
    }

    public function unread_render($user = 0)
    {

        if($user != 0){

            $user = \App\Models\User::find($user);

        } else {
            if (!auth()->check()) {
                return $this->wrong_permissions();
            } else {
                $user = auth()->user();
            }

        }

        $output = array();

        $tickers = \App\Models\Watchlist::where("user_id", $user->id)->get();

        foreach($tickers as $ticker){

        if ($ticker) {

            $instru = \App\Models\InstrumentGroup::find($ticker->instrument_group_id);

            if ($instru) {

                if ($ticker->last_id_read != "" and is_int($ticker->last_id_read)) {
                    $objects = \App\Models\Headline::where("id", ">", $ticker->last_id_read);

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
                    $objects->where("relevance",">=",$ticker->filter);

                    $output[$ticker->ticker] = $objects->count();


                } else {

                    $instruments = array($instru->id);
                    $objects = null;
                    if ($instruments) {
                        $filt = array();
                        if (is_array($instruments)) {
                            $filt = $instruments;
                        } else {
                            $filt = explode(",", $instruments);
                        }

                        if (is_array($filt) and count($filt) > 0) {

                            $objects = \App\Models\Headline::where(function ($query) use ($filt) {
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
                    $objects->where("relevance",">=",$ticker->filter);
                    if($ticker->last_id_read == 0 || $ticker->last_id_read == ""){
                        $objects->where("created_at",">=",Carbon::now()->subDays(7));
                    }
                    $output[$ticker->ticker] = $objects->count();

                }
            }
        }
    }

    return $output;

    }

    public function search(Request $request)
    {


        $user = null;
        if (!auth()->check()) {
            return $this->wrong_permissions();
        } else {
            $user = auth()->user();
        }

        $my_list =  \App\Models\Watchlist::select("instrument_group_id")->where("user_id", auth()->user()->id)->get();

        $objects = \App\Models\InstrumentGroup::where(function ($raw) use ($request) {
            $raw->orWhere(function ($query) use ($request) {
                $query->orWhere("name", "like", $request->text . "%");
                $query->orWhere("name", "like", "%" . $request->text);
                $query->orWhere("name", "like", "%" . $request->text . "%");
            })->orWhere(function ($query) use ($request) {
                $query->orWhere("context_name", "like", $request->text . "%");
                $query->orWhere("context_name", "like", "%" . $request->text);
                $query->orWhere("context_name", "like", "%" . $request->text . "%");
            });
        })->whereNotIn("id", $my_list)->orderBy("name", "ASC")->get();


        $output = $objects;


        return response()->json([
            'data' => [
                'message' => '',
                'status' => 'success',
                'response' => $output,
            ]
        ], 200);
    }

    public function add(Request $request)
    {


        $user = null;
        if (!auth()->check()) {
            return $this->wrong_permissions();
        } else {
            $user = auth()->user();
        }

        $objects = \App\Models\Watchlist::where("user_id", auth()->user()->id)->where("instrument_group_id", $request->id)->orderBy("name", "ASC")->get();
        if (count($objects) > 0) {
            return response()->json([
                'data' => [
                    'message' => 'You already have this item in your watchlist',
                    'status' => 'error',
                    'response' => "",
                ]
            ], 200);
        }

        $object = new \App\Models\Watchlist;
        $object_instrument = \App\Models\InstrumentGroup::find($request->id);
        if ($object_instrument) {
            $object->user_id = auth()->user()->id;
            $object->instrument_group_id = $request->id;
            $object->ticker = $object_instrument->name;
            $object->name = $object_instrument->context_name;
            $object->save();

            $stock = \App\Models\Stock::where("ticker",$object->ticker)->first();
            if(!$stock){
                $stock = new \App\Models\Stock;
                $stock->ticker = $object->ticker;
                $stock->active = 1;
                $stock->save();
            } else {
                $stock->active = 1;
                $stock->save();
            }


            return response()->json([
                'data' => [
                    'message' => 'Stock Added',
                    'status' => 'success',
                    'response' => "",
                ]
            ], 200);
        } else {
            return response()->json([
                'data' => [
                    'message' => 'Something went wrong',
                    'status' => 'error',
                    'response' => "",
                ]
            ], 200);
        }
    }



    public function remove(Request $request)
    {


        $user = null;
        if (!auth()->check()) {
            return $this->wrong_permissions();
        } else {
            $user = auth()->user();
        }

        $object = \App\Models\Watchlist::find($request->id);
        if ($object) {
            $object->delete();

            return response()->json([
                'data' => [
                    'message' => 'Stock Deleted',
                    'status' => 'success',
                    'response' => "",
                ]
            ], 200);
        } else {
            return response()->json([
                'data' => [
                    'message' => 'There was a problem',
                    'status' => 'error',
                    'response' => "",
                ]
            ], 200);
        }
    }

    public function favorite(Request $request)
    {


        $user = null;
        if (!auth()->check()) {
            return $this->wrong_permissions();
        } else {
            $user = auth()->user();
        }

        $object = \App\Models\Watchlist::find($request->id);
        if ($object) {
            if($object->favorite == 0){
                $object->favorite = 1;
            } else {
                $object->favorite = 0;
            }
            $object->save();

            return response()->json([
                'data' => [
                    'message' => 'Stock Updated',
                    'status' => 'success',
                    'response' => "",
                ]
            ], 200);
        } else {
            return response()->json([
                'data' => [
                    'message' => 'There was a problem',
                    'status' => 'error',
                    'response' => "",
                ]
            ], 200);
        }
    }
}
