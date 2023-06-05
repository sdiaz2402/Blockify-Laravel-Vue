<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function wrong_permissions(){
        return response()->json([
            'data' => [
                'message' => 'You don\'t have access',
                'status' => 'error',
                'response' =>  ""
            ]
        ], 200);
    }

    public function soft_error(){
        return response()->json([
            'data' => [
                'message' => 'Something went wrong. Our eng team has been notified.',
                'status' => 'error',
                'response' =>  ""
            ]
        ], 500);
    }

    public function hack_attempt(){
        return response()->json([
            'data' => [
                'message' => 'Something went wrong. Our eng team has been notified.',
                'status' => 'error',
                'response' =>  ""
            ]
        ], 200);
    }
}
