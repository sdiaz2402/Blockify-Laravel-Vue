<?php

namespace App\Libraries;

use Auth;
use DB;
use Log;
use Storage;

class Helper
{

    public static function list_coma_string($string = "")
    {
        return str_replace(",", ", ", $string);
    }

    public static function in_array_icase($needle, $haystack)
    {
        return in_array(strtolower($needle), array_map('strtolower', $haystack));
    }

    public static function match($needles, $haystack)
    {

        if (!is_array($needles)) {
            $needles = explode(",", $needles);
        }
        foreach ($needles as $needle) {
            if (stripos($haystack, trim($needle)) !== false) {
                return $needle;
            }
        }
        return "";
    }

    public static function match_words($needles, $haystack)
    {
        if (!is_array($needles)) {
            $needles = explode(",", $needles);
        }
        if (!is_array($haystack)) {
            $haystack = explode(" ", $haystack);
        }

        $needles = array_map('trim',$needles);
        $haystack = array_map('trim',$haystack);

        $result = array_intersect(array_map('strtolower', $needles), array_map('strtolower', $haystack));

        // if(count($result) > 0){
        //     return $result;
        // }

        return $result;
    }



    public static function cleanSubject($text)
    {
        $encoding = mb_detect_encoding($text, mb_detect_order(), false);

        if ($encoding == "UTF-8") {
                $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
            }


        $out = iconv(mb_detect_encoding($text, mb_detect_order(), false), "UTF-8//IGNORE", $text);


        return $out;
    }

    public static function sanitizeText($text)
    {
        $text = self::cleanSubject($text);
        $text = preg_replace('/[[:^print:]]/', '', $text);
        $text = preg_replace('/([0-9|#][\x{20E3}])|[\x{00ae}|\x{00a9}|\x{203C}|\x{2047}|\x{2048}|\x{2049}|\x{3030}|\x{303D}|\x{2139}|\x{2122}|\x{3297}|\x{3299}][\x{FE00}-\x{FEFF}]?|[\x{2190}-\x{21FF}][\x{FE00}-\x{FEFF}]?|[\x{2300}-\x{23FF}][\x{FE00}-\x{FEFF}]?|[\x{2460}-\x{24FF}][\x{FE00}-\x{FEFF}]?|[\x{25A0}-\x{25FF}][\x{FE00}-\x{FEFF}]?|[\x{2600}-\x{27BF}][\x{FE00}-\x{FEFF}]?|[\x{2900}-\x{297F}][\x{FE00}-\x{FEFF}]?|[\x{2B00}-\x{2BF0}][\x{FE00}-\x{FEFF}]?|[\x{1F000}-\x{1F6FF}][\x{FE00}-\x{FEFF}]?/u', '', $text);
        $text = preg_replace('/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/', '', $text);
        return $text;
    }

    public static function cleanHeadline($text){
        $re_handle = "/@[A-Za-z0-9_]{1,15}/";
        $re_url = "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i";
        $re_dollar = "/\\$[a-z][a-z0-9]+/i";

        $text = preg_replace( $re_handle, "", $text );
        $text = preg_replace( $re_url, "", $text );
        $text = preg_replace( $re_dollar, "", $text );

        $text = str_replace("#","",$text);

        return $text;


    }



    public static function cleanName($output)
    {
        $output = preg_replace('/[^A-Za-z0-9\-]/', '', $output);
        $output = ucfirst(strtolower($output));
        return $output;
    }

    public static function author($author, $team_id = "")
    {

        $author = str_replace("'", '', $author);
        $author = str_replace("'", '', $author);
        $author = mb_decode_mimeheader($author);

        $string = preg_replace('/\<[\s\S]+?\>/', '', $author);
        $string = str_replace('"', '', $string);
        $string = str_replace("’", '', $string);

        $string = self::cleanUTFString($string);


        return $string;
    }

    public static function filterTitle($text, $author = "")
    {

        $output = $text;
        if (trim($author) == "Perrotta Franco (IOTAF INSTITUTE TRAD)" or $author == "BBG") {
            $output_array = explode(":", $text);

            if (count($output_array) > 0) {
                array_shift($output_array);
                $output = implode("", $output_array);
            }
        }

        return $output;
    }

    public static function cleanUTFString($string)
    {
        $regex = <<<'END'
/
  (
    (?: [\x00-\x7F]                 # single-byte sequences   0xxxxxxx
    |   [\xC0-\xDF][\x80-\xBF]      # double-byte sequences   110xxxxx 10xxxxxx
    |   [\xE0-\xEF][\x80-\xBF]{2}   # triple-byte sequences   1110xxxx 10xxxxxx * 2
    |   [\xF0-\xF7][\x80-\xBF]{3}   # quadruple-byte sequence 11110xxx 10xxxxxx * 3
    ){1,100}                        # ...one or more times
  )
| .                                 # anything else
/x
END;



        $content = preg_replace($regex, '$1', $string);

        return $content;
    }

    public static function renameCoin($value){
        $array = explode("_",$value);
        $display = $array[0]." ".$array[2]."/".$array["3"];
        return $display;
    }

    public static function ellipsis($str, $max = 300)
    {
        $str = trim($str);
        if (strlen($str) > $max) {
            $s_pos = strpos($str, ' ');
            $cut = $s_pos === false || $s_pos > $max;
            $str = wordwrap($str, $max, ';;', $cut);
            $str = explode(';;', $str);
            $str = $str[0] . '...';
        }
        return $str;
    }

    public static function formatURLString($string)
    {
        return strtolower(trim(preg_replace('~[^0-9a-z]+~i', '', html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8')), '-'));
    }

    public static function image($image, $disk = "public", $default = "", $external = "")
    {
        if(stripos($image,"1785112") >= -1){
            $test = "Diego";
        }
        if (Storage::disk($disk)->has($image)) {
            return Storage::disk($disk)->url($image);
        }
        if ($image != null && file_exists(public_path() . $image)) {
            return $image;
        } else {
            if ($default != "") {
                if ($external != "") {
                    return url($default);
                } else {
                    return $default;
                }
            } else {
                return "/images/avatar.png";
            }
        }
    }

    public static function executionTime()
    {
        ini_set("max_execution_time", 280);
        ini_set("memory_limit", -1);
    }

    public static function objectToArray($d)
    {
        if (is_object($d)) {
            // Gets the properties of the given object
            // with get_object_vars function
            $d = get_object_vars($d);
        }

        if (is_array($d)) {
            /*
     * Return array converted to object
     * Using __FUNCTION__ (Magic constant)
     * for recursive call
     */
            return array_map(__FUNCTION__, $d);
        } else {
            // Return array
            return $d;
        }
    }

    public static function text($text, $chars)
    {
        if (strlen($text) > $chars - 3) {
            return substr($text, 0, $chars - 3) . "...";
        } else {
            return $text;
        }
    }

    public static function arrayToObject($d)
    {
        if (is_array($d)) {
            /*
         * Return array converted to object
         * Using __FUNCTION__ (Magic constant)
         * for recursive call
         */
            return (object)array_map(__FUNCTION__, $d);
        } else {
            // Return object
            return $d;
        }
    }

    public static function date($date)
    {
        $dateTime = new \DateTime($date, new \DateTimeZone(\Config::get('app.timezone')));
        if (Auth::check()) {
            if (Auth::user()->timezone == "") {
                Auth::user()->timezone = "America/New_York";
                Auth::user()->save();
            }
            $dateTime->setTimezone(new \DateTimeZone(Auth::user()->timezone));
        }
        //return $dateTime->format('Y-m-d'); // for example;
        return $dateTime->format('M j, Y'); // for example;
    }

    public static function eventDate($date, $format = "Y M D d")
    {
        $dateTime = new \DateTime($date, new \DateTimeZone(\Config::get('app.timezone')));
        if (Auth::check()) {
            if (Auth::user()->timezone == "") {
                Auth::user()->timezone = "America/New_York";
                Auth::user()->save();
            }
            $dateTime->setTimezone(new \DateTimeZone(Auth::user()->timezone));
        }
        //return $dateTime->format('Y-m-d'); // for example;
        return $dateTime->format($format); // for example;
    }

    public static function convertTimeToUTCzone($str, $userTimezone = "America/New_York", $format = 'Y-m-d H:i:s')
    {

        $new_str = new \DateTime($str, new \DateTimeZone($userTimezone));
        $new_str->setTimeZone(new \DateTimeZone('UTC'));
        return $new_str->format($format);
    }


    //this function converts string from UTC time zone to current user timezone
    public static function convertTimeToUSERzone($str, $userTimezone = "America/New_York", $format = 'Y-m-d H:i:s')
    {
        try {
            if (empty($str)) {
                return '';
            }

            if (!($str instanceof DateTime)) {
                $new_str = new \DateTime($str, new \DateTimeZone('UTC'));
            } else {
                $new_str = $str;
            }
            $new_str->setTimeZone(new \DateTimeZone($userTimezone));
            return $new_str->format($format);
        } catch (\Exception $e) {
            logger()->error("Bad Timezone");
            logger()->error($userTimezone);
            logger()->error($e);
            return "";
        }
    }

    public static function convertUTCtoEST($date)
    {

        $date->setTimeZone(new \DateTimeZone("America/New_York"));
        return $date;
    }

    public static function toDate($date)
    {
        $dateTime = new \DateTime($date, new \DateTimeZone(\Config::get('app.timezone')));
        if (Auth::check() and Auth::user()->timezone != "") {
            $dateTime = new \DateTime($date, new \DateTimeZone(Auth::user()->timezone));
        }
        $dateTime->setTimezone(new \DateTimeZone(\Config::get('app.timezone')));

        return $dateTime->format('Y-m-d'); // for example;
    }

    public static function displayTime($seconds)
    {
        $seconds = intval(preg_replace('/[^\d.]/', '', $seconds));
        if ($seconds > 3600) {
            $time = self::formatDec($seconds / 3600);
            return $time . " hours";
        } else if ($seconds > 60) {
            $time = self::formatDec($seconds / 60);
            return $time . " min";
        } else {
            return $seconds . " sec";
        }
    }

    public static function toDateTime($date)
    {
        $dateTime = new DateTime($date, new DateTimeZone(\Config::get('app.timezone')));
        if (Auth::check() and Auth::user()->timezone != "") {
            $dateTime = new DateTime($date, new DateTimeZone(Auth::user()->timezone));
        }
        $dateTime->setTimezone(new DateTimeZone(\Config::get('app.timezone')));

        return $dateTime->format('Y-m-d H:i:s'); // for example;
    }


    public static function dateTime($date, $format = "M j, Y, g:i a", $user_id = 0)
    {
        //return date("yy-m-d",strtotime($date))->timezone(Auth::user()->timezone);
        $user_object = Auth::user();
        if ($user_id != 0 and $user_id != "") {
            $user = \App\Models\User::find($user_id);
            if ($user) {
                $user_object = $user;
            }
        }
        $dateTime = new \DateTime($date, new \DateTimeZone(\Config::get('app.timezone')));
        if ($user_object and $user_object->timezone != "") {
            $dateTime->setTimezone(new \DateTimeZone($user_object->timezone));
        }

        return $dateTime->format($format); // for example;
    }

    public static function validateDateTime($dateStr, $format)
    {
        date_default_timezone_set('UTC');
        $date = \DateTime::createFromFormat($format, $dateStr);
        return $date && ($date->format($format) === $dateStr);
    }

    public static function days($date)
    {
        //return date("yy-m-d",strtotime($date))->timezone(Auth::user()->timezone);
        $dateTime = new \DateTime($date, new \DateTimeZone(\Config::get('app.timezone')));
        $today = new \DateTime(date("Y-m-d H:i:s"), new \DateTimeZone(\Config::get('app.timezone')));
        if (Auth::check() and Auth::user()->timezone != "") {
            $dateTime->setTimezone(new \DateTimeZone(Auth::user()->timezone));
            $today->setTimezone(new \DateTimeZone(Auth::user()->timezone));
        }

        $diff = $dateTime->diff($today)->format("%a");
        return $diff;
    }

    public static function timeAgo($date)
    {
        //return date("yy-m-d",strtotime($date))->timezone(Auth::user()->timezone);
        $dateTime = new \DateTime($date, new \DateTimeZone(\Config::get('app.timezone')));
        $today = new \DateTime(date("Y-m-d H:i:s"), new \DateTimeZone(\Config::get('app.timezone')));
        if (Auth::check() and Auth::user()->timezone != "") {
            $dateTime->setTimezone(new \DateTimeZone(Auth::user()->timezone));
            $today->setTimezone(new \DateTimeZone(Auth::user()->timezone));
        }

        $diff["days"] = $dateTime->diff($today)->format("%a");
        $diff["hours"] = $dateTime->diff($today)->format("%h");
        $diff["minutes"] = $dateTime->diff($today)->format("%i");
        $diff["seconds"] = $dateTime->diff($today)->format("%s");

        return $diff;
    }

    public static function extractYoutubeTag($url)
    {
        if (strpos($url, 'v=') !== false) {
            $my_array_of_vars = array();
            parse_str(parse_url($url, PHP_URL_QUERY), $my_array_of_vars);
            return $my_array_of_vars['v'];
        } else {
            $url_array = explode("/", $url);


            if (count($url_array) > 2) {
                return $url_array[3];
            }
            return "";
        }
    }

    public static function formatPrice($price, $dec = 0, $dollar = false, $force = false)
    {
        $decimals = 2;
        if ($dec !== null) $decimals = $dec;
        $temp = floatval($price);
        $output = "";

        //default
        if ($force) {
            return number_format($price, $dec, ".", ",");
        } else {
            if ($temp < 0.01) {
                $output = number_format($price, 8, ".", ",");
            } else if ($temp < 10) {
                $output = number_format($price, 4, ".", ",");
            } else if ($temp > 1000) {
                $output = number_format($price, 0, ".", ",");
            } else if ($temp > 100) {
                $output = number_format($price, 1, ".", ",");
            } else {
                $output = number_format($price, 2, ".", ",");
            }
        }

        if (!$dollar) return $output;
        return "$" . $output;
    }

    public static function change($open, $low, $high, $current, $dec = 0)
    {

        $center = $high - $low;
        $offset = $current - $open;
        $change = $offset / $open * 100;


        $change = number_format($change, $dec);
        return $change;
    }

    public static function colorNumber($price, $bias_b = false, $bias = 0)
    {

        if ($bias_b) {

            if ($bias > 0) {
                return "green";
            } elseif ($bias < 0) {
                return "red";
            } elseif ($bias == 0) {
                return "";
            }
        }

        if ($price > 0) {
            return "green";
        } elseif ($price < 0) {
            return "red";
        } else {
            return "";
        }
    }

    public static function formatDec($price, $dec = 0)
    {
        $price = number_format($price, $dec);
        return $price;
    }

    public static function cleanStringToNumber($price)
    {
        $price = str_replace("$", "", $price);
        if (strpos($price, '(') !== false) {
            $price = str_replace("(", "", $price);
            $price = str_replace(")", "", $price);
            $price = $price * -1;
        }
        $price = str_replace("'", ".", $price);
        $price = str_replace(",", "", $price);
        $price = number_format($price, 2);
        return $price;
    }


    public static function dateToUnix($date)
    {
        return strtotime($date);
    }

    public static function unixToDate($date)
    {
        return date("Y-m-d H:i:s", $date);
    }

    public static function prepareFileSystemUser($userId)
    {

        //  logger()->error("storage");
        if (!Storage::disk("public")->has($userId)) {
            //  logger()->error("storage1");
            Storage::disk("public")->makeDirectory($userId, 0775, true);
            //  logger()->error("storage2");

            $directory = $userId . "/profile";

            if (!Storage::disk("public")->has($directory)) {
                //  logger()->error("storage3");
                Storage::disk("public")->makeDirectory($directory, 0775, true);
            }
        }
    }

    public static function randomPassword($length = 10)
    {
        $random = str_shuffle('abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ234567890!$%^&!$%^&');
        $password = substr($random, 0, $length);
        return $password;
    }


    public static function saveImage($image, $destination, $userId = null,$crop=true)
    {

        // logger()->error("Prepaing folder");
        if ($userId) static::prepareFileSystemUser($userId);


        $pathImg = "";
        $pathOriginal = "";
        $pathThumb = "";

        $img = \Image::make($image);
        $original = \Image::make($image);
        $thumb = \Image::make($image);

        $name = guid();

        $pathImg = $destination . "/" . $name . ".jpg";
        $pathOriginal = $destination . "/" . $name . "_original.jpg";
        $pathThumb = $destination . "/" . $name . "_thumb.jpg";




        if ($thumb->width() > \Config::get("constants.thumbSize") || $thumb->height() > \Config::get("constants.thumbSize")) {
            $thumb->fit(\Config::get("constants.thumbSize"), \Config::get("constants.thumbSize"));
        }

        if($crop){

            if ($img->width() > \Config::get("constants.displaySize") || $img->height() > \Config::get("constants.displaySize")) {
                $img->fit(\Config::get("constants.displaySize"), \Config::get("constants.displaySize"));
            }

        }


        Storage::disk("public")->put($pathImg, $img->encode());
        Storage::disk("public")->put($pathOriginal, $original->encode());
        Storage::disk("public")->put($pathThumb, $thumb->encode());



        return array("original" => $pathOriginal, "image" => $pathImg, "thumb" => $pathThumb);
    }


    public static function getAge($birthDate)
    {

        $datetime1 = new DateTime(date("Y-m-d H:i:s"));
        $datetime2 = new DateTime($birthDate);
        $interval = $datetime1->diff($datetime2);
        return $interval->format('%y');
    }




    public static function getTypeOfCall($userId)
    {

        if ($userId === null or $userId == "") return "";
        if (is_numeric($userId)) {

            if ($userId != Auth::user()->id) {
                return "External";
            }
            return "";
        } else {
            if ($userId->id != Auth::user()->id) {
                return "External";
            }
            return "";
        }
    }

    // public static function formatPhone($phone){

    //         if($phone != ""){

    //             $rx = "/
    //         (1)?\D*     # optional country code
    //         (\d{3})?\D* # optional area code
    //         (\d{3})\D*  # first three
    //         (\d{4})     # last four
    //         (?:\D+|$)   # extension delimiter or EOL
    //         (\d*)       # optional extension
    //     /x";
    //     preg_match($rx, $phone, $matches);
    //     if(!isset($matches[0])) return false;

    //     $country = $matches[1];
    //     $area = $matches[2];
    //     $three = $matches[3];
    //     $four = $matches[4];
    //     $ext = $matches[5];

    //     $out = "$three-$four";
    //     if(!empty($area)) $out = "$area-$out";
    //     if(!empty($country)) $out = "+$country-$out";
    //     if(!empty($ext)) $out .= "x$ext";

    //     // check that no digits were truncated
    //     // if (preg_replace('/\D/', '', $s) != preg_replace('/\D/', '', $out)) return false; +1-438-407-4748
    //     return $out;
    // } else {
    //     return "";
    // }
    // }
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

            $phoneNumber = $areaCode . '-' . $nextThree . '-' . $lastFour;
        } else if (strlen($phoneNumber) == 7) {
            $nextThree = substr($phoneNumber, 0, 3);
            $lastFour = substr($phoneNumber, 3, 4);

            $phoneNumber = $nextThree . '-' . $lastFour;
        }

        return $phoneNumber;
    }

    public static function pre()
    {
        echo "<pre>";
    }

    public static function printLastQuery($lines = 1)
    {
        $queries = DB::getQueryLog($lines);
        echo "<pre>";
        print_r(array_reverse($queries));
        echo "</pre>";
    }

    public static function prep()
    {
        echo "</pre>";
    }

    public static function now()
    {
        return date("Y-m-d H:i:s");
    }

    public static function nowDate()
    {
        return date("Y-m-d");
    }


    public static function startOfDay()
    {
        return date("Y-m-d") . " 00:00:00";
    }

    public static function endOfDay()
    {
        return date("Y-m-d") . " 23:59:59";
    }

    public static function CurlRequest($url)
    {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        $info   = curl_getinfo($ch);
        if ($output === false) {
                if (curl_error($ch)) {
                        throw new Exception(curl_error($ch));
                    } else {
                        throw new Exception($info);
                    }
            }
        $json_data = json_decode($output);
        if ($json_data == null) {
                // json parsing failed
                throw new Exception($output);
            }
        $http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_status_code != 200) {
                throw new Exception($json_data);
            }
        curl_close($ch);
        return $json_data;
    }

    public static $price_tables = array(
        "1m" => "t_one_min_archives",
        "5m" => "t_five_min_archives",
        "30m" => "t_thirty_min_archives",
        "1h" => "t_one_hour_archives",
        "1d" => "t_one_day_archives",
        "1w" => "t_one_week_archives",
    );

    public static function default_price($price){
        $price_array = explode("_",$price);
        $output = $price_array[0]."_".$price_array[2].$price_array[3]."_p_1min";
        return $output;
    }


    public static function grade_price($total_value, $minimum, $maximum, $direction)
    {
        $min_dec = 80;
        $max_dec = 200;
        $green_base = "#33f203";
        $red_base = "#F80101";
        $output = "";

        $temp = $total_value / $maximum;



        $temp2 = $max_dec * $temp;

        if ($temp2 > $max_dec) $temp2 = $max_dec;

        //CONVERT TO HEX

        if (strtolower($direction) == "buy") {
            $output = $green_base;
        } else {
            $output = $red_base;
        }


        $hex = dechex($temp2);



        $hex = str_pad($hex, 2, "0", STR_PAD_LEFT);

        $output = $output . $hex;

        return ' style="background-color:' . $output . '!important"';
    }

    public static function format_liquidation($text) {

        try{

            if (stripos($text,"Liquid") >= 0 and stripos($text,"signal") ==  false) {

                //Liquidated long on ETHUSD: Sell 800 @ 139.45  ~ Double kill

                $text_split = explode(" ",$text);
                $short = $text_split[1];
                $ins = $text_split[3];
                $ins = str_replace(":","",$ins);
                $units = $text_split[5];
                $price = $text_split[7];

                $totalUSD = floatval($units) * floatval($price);

                $totalUSD = number_format($totalUSD,0);

                $output = "Liquidated ".strtoupper($short)." ".$ins." ".$totalUSD." USD ";


                if($totalUSD == 0){
                    $output = $text;
                }

                return $output;

            } else {


                return $text;
            }

        } catch(\Exception $e){


            Log::error($e);
            return $text;

        }

    }

}
