<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShareController extends Controller
{
    //

    public function remove(Request $request)
    {


        $user = null;
        if (!auth()->check()) {
            return $this->wrong_permissions();
        } else {
            $user = auth()->user();
        }


        $objects = \App\Models\Share::where("suggest",1)->where("user_id",$user->id)->where("email",$request->email)->get();

        foreach($objects as $object){
            $object->suggest = 0;
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

    public function search(Request $request)
    {


        $user = null;
        if (!auth()->check()) {
            return $this->wrong_permissions();
        } else {
            $user = auth()->user();
        }


        $objects = \App\Models\Share::select("email")->where("user_id",$user->id)->where(function ($raw) use ($request) {
            $raw->orWhere(function ($query) use ($request) {
                $query->orWhere("email", "like", $request->email . "%");
                $query->orWhere("email", "like", "%" . $request->email);
                $query->orWhere("email", "like", "%" . $request->email . "%");
            });
        })->where("suggest",1)->distinct("email")->orderBy("email", "ASC")->get();


        $output = $objects;


        return response()->json([
            'data' => [
                'message' => '',
                'status' => 'success',
                'response' => $output,
            ]
        ], 200);
    }


}
