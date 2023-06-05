<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Cloud\Iot\V1\DeviceManagerClient;

class SourceController extends Controller
{
    //
    public function list(Request $request)
    {


        $user = null;
        if (!auth()->check()) {
            return $this->wrong_permissions();
        } else {
            $user = auth()->user();
        }

        $objects = null;

        if($request->letter != ""){
            $objects = \App\Models\Source::orderBy("name", "ASC")->where("name","like",$request->letter."%")->get();
        } else {
            $objects = \App\Models\Source::orderBy("name", "ASC")->whereNotNull("rss")->get();
        }

        $output = $objects;


        return response()->json([
            'data' => [
                'message' => '',
                'status' => 'success',
                'response' => $output,
            ]
        ], 200);
    }

    public function list_sources_category(Request $request)
    {


        $user = null;
        if (!auth()->check()) {
            return $this->wrong_permissions();
        } else {
            $user = auth()->user();
        }

        $objects = null;


        $objects = \App\Models\SourceService::select("sources.name","sources.rss","sources.url","source_services.*")->leftJoin("sources","source_id","sources.id")->orderBy("name", "ASC")->whereNotNull("rss")->get();

        $output = $objects;


        return response()->json([
            'data' => [
                'message' => '',
                'status' => 'success',
                'response' => $output,
            ]
        ], 200);
    }

    public function mark_read(Request $request)
    {


        $user = null;
        if (!auth()->check()) {
            return $this->wrong_permissions();
        } else {
            $user = auth()->user();
        }

        $object = \App\Models\Watchlist::where("ticker",$request->ticker)->first();

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

    public function update_rank(Request $request)
    {

        $object = \App\Models\Source::find($request->id);

        if($object){

            $object->rank = $request->rank;
            $object->save();

        }

        return response()->json([
            'data' => [
                'message' => 'Rank Updated',
                'status' => 'success',
                'response' => "",
            ]
        ], 200);
    }

    public function update_rss(Request $request)
    {

        $object = \App\Models\Source::find($request->id);

        if($object){

            $object->rss = $request->rss;
            $object->save();

        }

        return response()->json([
            'data' => [
                'message' => 'Rank Updated',
                'status' => 'success',
                'response' => "",
            ]
        ], 200);
    }

    public function update_auto(Request $request)
    {

        $object = \App\Models\Source::find($request->id);

        if($object){

            $object->auto_added = $request->auto;
            $object->save();

        }

        return response()->json([
            'data' => [
                'message' => 'Rank Updated',
                'status' => 'success',
                'response' => "",
            ]
        ], 200);
    }

    public function unread(Request $request)
    {

        $output = array();

        $tickers = \App\Models\Watchlist::where("user_id", auth()->user()->id)->get();

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
}
