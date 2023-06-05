<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Cloud\Iot\V1\DeviceManagerClient;

class StockController extends Controller
{
    //
    public function list_filters()
    {


        $user = null;
        if (!auth()->check()) {
            return $this->wrong_permissions();
        } else {
            $user = auth()->user();
        }

        $objects = \App\Models\InstrumentgroupsFilter::select("instrumentgroups_filters.*","instrument_groups.name","instrument_groups.context_name")->leftJoin("instrument_groups","instrumentgroups_filters.instrument_group_id","=","instrument_groups.id")->orderBy("name", "ASC")->get();
        $output = $objects;


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

        $object = \App\Models\InstrumentgroupsFilter::find($request->id);

        if($object){

            $object->keywords = $request->keywords;
            $object->save();

        }

        return response()->json([
            'data' => [
                'message' => 'Keywords Updated',
                'status' => 'success',
                'response' => "",
            ]
        ], 200);
    }
}
