<?php

namespace App\Http\Controllers;

use App\Events\NewUser;
use App\Notifications\SendPassword;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Messages\MailMessage;
use Notification;
use Illuminate\Support\Str;
use Spatie\Searchable\Search;
use Spatie\Searchable\ModelSearchAspect;

class UserController extends Controller
{
    //


    function add_user(Request $request)
    {

        try {
            $perm =  \App\Libraries\Diego::checkPermisions($request->p, config("constants.PERM_ADMIN"));
            if (!$perm) {
                return $this->wrong_permissions();
            }

            if ($request->p["l"] < $request->level) {
                return $this->hack_attempt();
            }

            $new = new \App\Models\User;
            $new->first_name = $request->get("first_name");
            $new->last_name = $request->get("last_name");
            $new->email = $request->get("email");
            $new->phone = \App\Libraries\Diego::formatPhone($request->get("phone"));

            $clean_pass = Str::random(8);
            $new->password = bcrypt($clean_pass);


            $new->save();


            event(new NewUser($new->id));

            // if ($new->email) {
            //     $new->notify(new SendPassword($clean_pass));
            // }


            return response()->json([
                'data' => [
                    'message' => 'User has been added',
                    'status' => 'success',
                    'response' => $new,
                ]
            ], 200);
        } catch (\Exception $e) {
            logger()->error($e);
            return $this->soft_error();
        }
    }

    function newsletter(Request $request)
    {
    }

    public function remove_reader(Request $request)
    {


        $user = null;
        if (!auth()->check()) {
            return $this->wrong_permissions();
        } else {
            $user = auth()->user();
        }

        $object = \App\Models\Reader::where("user_id",$user->id)->where("follows",$request->id)->first();
        if ($object) {
            $object->delete();

            return response()->json([
                'data' => [
                    'message' => 'User Deleted',
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

    function remove_user(Request $request)
    {

        try {
            $perm =  \App\Libraries\Diego::checkPermisions($request->p, config("constants.PERM_ADMIN"));
            if (!$perm) {
                return $this->wrong_permissions();
            }

            $user = \App\Models\User::find($request->id);
            $user->delete();



            return response()->json([
                'data' => [
                    'message' => 'User has been removed',
                    'status' => 'success',
                    'response' => "",
                ]
            ], 200);
        } catch (\Exception $e) {
            logger()->error($e);
            return $this->soft_error();
        }
    }

    function checknickname(Request $request)
    {

        $user = null;
        $user_id = 0;
        if (!auth()->check()) {
            $user_id = 0;
        } else {
            $user = auth()->user();
            $user_id = $user->id;
        }

        if($request->nickname == ""){
            return response()->json([
                'data' => [
                    'message' => 'Cannot be empty',
                    'status' => 'error',
                    'response' => "",
                ]
            ], 200);
        }
        $nickname_check  = \App\Models\User::where("nickname", $request->nickname)->where("id","!=",$user_id)->count();
        if ($nickname_check != 0) {
            return response()->json([
                'data' => [
                    'message' => 'That nickname is already used by another user',
                    'status' => 'error',
                    'response' => "",
                ]
            ], 200);
        } else {
            return response()->json([
                'data' => [
                    'message' => '',
                    'status' => 'success',
                    'response' => "",
                ]
            ], 200);
        }
    }

    function save_user(Request $request)
    {

        try {
            // $perm =  \App\Libraries\Diego::checkPermisions($request->p, config("constants.PERM_USER"));
            // if (!$perm) {
            //     return $this->wrong_permissions();
            // }

            $user = null;
            if (\Auth::check()) {
                $user = $user = \Auth::user();
            } else {
                logger()->error($user);
            }


            if ($request->nickname != "") {
                $nickname_check  = \App\Models\User::where("nickname", $request->nickname)->where("id","!=",$user->id)->count();
                // $nickname_check  = \App\Models\User::where("nickname", $request->nickname)->count();
                if ($nickname_check != 0) {
                    return response()->json([
                        'data' => [
                            'message' => 'That nickname is already used by another user',
                            'status' => 'error',
                            'response' => "",
                        ]
                    ], 200);
                }
            }


            if ($user) {
                $user->first_name = $request->first_name;
                $user->last_name = $request->last_name;
                $user->nickname = $request->nickname;
                // $user->phone = \App\Libraries\Diego::formatPhone($request->phone);

                if ($request->password != "") {
                    $user->password = bcrypt($request->password);
                }


                $user->save();
            }

            return response()->json([
                'data' => [
                    'message' => 'User has been saved',
                    'status' => 'success',
                    'response' => "",
                ]
            ], 200);
        } catch (\Exception $e) {
            logger()->error($e);
            return $this->soft_error();
        }
    }

    function save_notifications(Request $request)
    {

        try {
            // $perm =  \App\Libraries\Diego::checkPermisions($request->p, config("constants.PERM_USER"));
            // if (!$perm) {
            //     return $this->wrong_permissions();
            // }

            $user = null;
            if (\Auth::check()) {
                $user = $user = \Auth::user();
            } else {
                logger()->error($user);
            }

            if ($user) {
                $user->mid_session = $request->mid_session;
                $user->closing_session = $request->closing_session;
                $user->save();
            }

            return response()->json([
                'data' => [
                    'message' => 'Notifications have been saved',
                    'status' => 'success',
                    'response' => "",
                ]
            ], 200);
        } catch (\Exception $e) {
            logger()->error($e);
            return $this->soft_error();
        }
    }

    function reading_club(Request $request)
    {

        $user = null;
        if (\Auth::check()) {
            $user = $user = \Auth::user();
        } else {
            logger()->error($user);
        }

        try {
            $objects = \App\Models\Reader::select("users.first_name","users.last_name","users.id","readers.created_at")->leftJoin("users", "follows", "users.id")->where("user_id",$user->id)->orderBy("first_name", "ASC")->orderBY("last_name", "ASC")->get();
            return response()->json([
                'data' => [
                    'message' => '',
                    'status' => 'success',
                    'response' => $objects,
                ]
            ], 200);
        } catch (\Exception $e) {
            logger()->error($e);
            return $this->soft_error();
        }
    }
    function search_readers(Request $request)
    {

        $user = null;
        if (\Auth::check()) {
            $user = $user = \Auth::user();
        } else {
            logger()->error($user);
        }

        $friends = \App\Models\Reader::where("user_id",$user->id)->pluck("follows");

        try {
            $objects = \App\Models\User::select("users.first_name","users.last_name","users.id","nickname")
                            ->where("users.id","!=",$user->id);
                            if(count($friends) > 0){
                                $objects->whereNotIn("users.id",$friends);
                            }
                            $objects->where(function ($query) use ($request) {
                                $query->orWhere("first_name", "like", $request->text . "%");
                                $query->orWhere("first_name", "like", "%" . $request->text);
                                $query->orWhere("first_name", "like", "%" . $request->text . "%");
                                $query->orWhere("last_name", "like", "%" . $request->text . "%");
                                $query->orWhere("last_name", "like", "%" . $request->text . "%");
                                $query->orWhere("last_name", "like", "%" . $request->text . "%");
                                $query->orWhere("email", "like", "%" . $request->text . "%");
                                $query->orWhere("email", "like", "%" . $request->text . "%");
                                $query->orWhere("email", "like", "%" . $request->text . "%");
                                $query->orWhere("nickname", "like", "%" . $request->text . "%");
                                $query->orWhere("nickname", "like", "%" . $request->text . "%");
                                $query->orWhere("nickname", "like", "%" . $request->text . "%");
                            });
                            $objects->orderBy("first_name", "ASC")->orderBY("last_name", "ASC");
                            $objects = $objects->get();
            return response()->json([
                'data' => [
                    'message' => '',
                    'status' => 'success',
                    'response' => $objects,
                ]
            ], 200);
        } catch (\Exception $e) {
            logger()->error($e);
            return $this->soft_error();
        }
    }

    public function add_reader(Request $request)
    {


        $user = null;
        if (!auth()->check()) {
            return $this->wrong_permissions();
        } else {
            $user = auth()->user();
        }

        $objects = \App\Models\Reader::where("user_id", auth()->user()->id)->where("follows", $request->id)->get();
        if (count($objects) > 0) {
            return response()->json([
                'data' => [
                    'message' => 'You already have this user in your reading club',
                    'status' => 'error',
                    'response' => "",
                ]
            ], 200);
        }

        $object = new \App\Models\Reader;
        if ($object) {
            $object->user_id = auth()->user()->id;
            $object->follows = $request->id;
            $object->save();
        }


        return response()->json([
            'data' => [
                'message' => 'User Added',
                'status' => 'success',
                'response' => "",
            ]
        ], 200);

    }





    function get_user(Request $request)
    {
        $user = null;
        if (\Auth::check()) {
            $user = $user = \Auth::user();
        } else {
            logger()->error($user);
        }


        session(['user' => $user]);

        $output = array("user" => $user);
        return response()->json([
            'data' => [
                'message' => '',
                'status' => 'success',
                'response' => $output,
            ]
        ], 200);
    }

    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);


        if (auth()->attempt($credentials,true)) {

            $user = auth()->user();



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
                    'message' => 'Wrong login or password',
                    'status' => 'error',
                    'response' => "",
                ]
            ], 200);
        }
    }



    public function test()
    {
        print_r(":test");
    }
}
