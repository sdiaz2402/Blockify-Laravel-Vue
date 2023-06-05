<?php // Code within app\Helpers\Helper.php

namespace App\Libraries;

use Carbon\Carbon;
use Exception;
use Storage;
use Image;
use Log;

class Diego
{
    public static function shout(string $string)
    {
        return strtoupper($string);
    }

    public static function generatePin( $number ) {
        // Generate set of alpha characters
        // $alpha = array();
        // for ($u = 65; $u <= 90; $u++) {
        //     // Uppercase Char
        //     array_push($alpha, chr($u));
        // }

        // // Just in case you need lower case
        // // for ($l = 97; $l <= 122; $l++) {
        // //    // Lowercase Char
        // //    array_push($alpha, chr($l));
        // // }

        // // Get random alpha character
        // $rand_alpha_key = array_rand($alpha);
        // $rand_alpha = $alpha[$rand_alpha_key];

        // // Add the other missing integers
        // $rand = array($rand_alpha);
        $rand = array();
        for ($c = 0; $c < $number - 1; $c++) {
            array_push($rand, mt_rand(0, 9));
            shuffle($rand);
        }

        return implode('', $rand);
    }

    public static function generateTradeNo(){
        $date = Carbon::now("UTC")->format("YmdHisu");
        return substr($date, 0, -3);;
    }

    public static function formatPhone($phoneNumber)
    {
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        if (strlen($phoneNumber) > 10) {
            $countryCode = substr($phoneNumber, 0, strlen($phoneNumber) - 10);
            $areaCode = substr($phoneNumber, -10, 3);
            $nextThree = substr($phoneNumber, -7, 3);
            $lastFour = substr($phoneNumber, -4, 4);

            $phoneNumber = '+' . $countryCode . '-' . $areaCode . '-' . $nextThree . '-' . $lastFour;
        } else if (strlen($phoneNumber) == 10) {
            $areaCode = substr($phoneNumber, 0, 3);
            $nextThree = substr($phoneNumber, 3, 3);
            $lastFour = substr($phoneNumber, 6, 4);

            $phoneNumber = '+1' . '-' . $areaCode . '-' . $nextThree . '-' . $lastFour;
        } else if (strlen($phoneNumber) == 7) {
            $nextThree = substr($phoneNumber, 0, 3);
            $lastFour = substr($phoneNumber, 3, 4);

            $phoneNumber = $nextThree . '-' . $lastFour;
        }

        return $phoneNumber;
    }

    public static function getValidPhone($phoneNumber)
    {
        $phone = str_replace("-", "", $phoneNumber);
        $phone = str_replace(".", "", $phone);
        $phone = str_replace("+", "", $phone);
        if (strlen($phone) == 11) {
            return $phone;
        } else if (strlen($phone) == "10") {
            return "1" . $phone;
        } else {
            return null;
        }
    }


    public static function saveImage($image, $destination, $deviceId = null,$crop=false)
    {

        // logger()->error("Prepaing folder");
        if ($deviceId) static::prepareFileSystem($deviceId);


        $pathImg = "";
        $pathOriginal = "";
        $pathThumb = "";

        $img = Image::make($image);
        $original = Image::make($image);
        $thumb = Image::make($image);

        $name = guid();

        $pathImg = $destination . "/" . $name . ".jpg";
        $pathOriginal = $destination . "/" . $name . "_original.jpg";
        $pathThumb = $destination . "/" . $name . "_thumb.jpg";




        if ($thumb->width() > config("constants.thumbSize") || $thumb->height() > config("constants.thumbSize")) {
            $thumb->fit(\config("constants.thumbSize"), config("constants.thumbSize"));
        }

        if($crop){

            if ($img->width() > config("constants.displaySize") || $img->height() > config("constants.displaySize")) {
                $img->fit(config("constants.displaySize"), config("constants.displaySize"));
            }

        }


        Storage::disk("public")->put($pathImg, $img->encode());
        Storage::disk("public")->put($pathOriginal, $original->encode());
        Storage::disk("public")->put($pathThumb, $thumb->encode());



        return array("original" => $pathOriginal, "image" => $pathImg, "thumb" => $pathThumb);
    }

    public static function prepareFileSystem($deviceId)
    {

        //  logger()->error("storage");
        if (!Storage::disk("public")->has($deviceId)) {
            //  logger()->error("storage1");
            Storage::disk("public")->makeDirectory($deviceId, 0775, true);
            //  logger()->error("storage2");

            $directory = $deviceId . "/images";

            if (!Storage::disk("public")->has($directory)) {
                //  logger()->error("storage3");
                Storage::disk("public")->makeDirectory($directory, 0775, true);
            }
        }
    }

    public static function add_db_quotes($str)
    {
        return sprintf("`%s`", $str);
    }

    public static function dynamic_replace($message,$dict){
        foreach($dict as $k => $v){
            $message = str_replace("{".$k."}",$v,$message);
        }
        return $message;
    }

    public static function add_quotes($str)
    {
        return sprintf("'%s'", $str);
    }

    public static function checkPermisions($perm,$level,$author=0,$explicit=false){


        try{
            if(!is_array($perm)){
                $perm = json_decode($perm,true);
            }
            $origin=false;
            $level_=false;
            $user_id  = $perm["u"];
            $org_id = $perm["o"];
            $user_level = $perm["l"];

            if($user_id <= 0 or $org_id <= 0 or $user_level <= 0){
                return false;
            }

            if($author == $user_id) $origin = true;


            if($level <= $user_level){
                $level_=true;
            }  else {
                $level_=false;
            }
            if($explicit){
                return $level_ && $origin;
            } else {
                return $level_ || $origin;
            }

        } catch(Exception $e){
            logger()->error($e);
            return false;
        }



    }
}
