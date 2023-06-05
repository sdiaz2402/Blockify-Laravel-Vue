<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;
use MimeMailParser\Parser;
use Carbon\Carbon;
use Log;
use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use Feeds;
use App\Jobs\ProcessFeaturedImage;

class RssSyncCommand extends Command

{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rss_sync {block}';

    protected $proxies_list = array(
        "50.117.102.201:1212",
        "50.117.102.48:1212",
        "190.112.203.109:1212",
        "190.112.194.242:1212",
        "50.117.102.66:1212",
        "50.117.102.12:1212",
        "200.35.152.24:1212",
        "190.112.195.252:1212",
        "50.117.102.72:1212",
        "190.112.194.15:1212",
    );
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Receive RSS and create DB entries';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function find_image($data){


        foreach ($data->get_enclosures() as $item) {
            // print_r($item);
            if(property_exists($item,"type") and stripos($item->type,"image") !== false){
                return $item->link;
            }
        }

    }

    public function find_category($data){
        $output = "";
        foreach ($data->get_categories() as $item) {
            $output = $item->term;
        }

        return $output;



    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    ///opt/cpanel/ea-php71/root/usr/bin/php -q /home/iotaf/agora.iotaf.com/artisan email_parse

    public function handle()
    {



        $authors_id = array();
        $sources = array();
        $sources_sub = array();
        $sources_importance = array();
        $sources_aux = \App\Models\Source::orderBy("name")->get();
        foreach ($sources_aux as $sou) {
            $sources[strtolower($sou->name)] = $sou;
        }

        $tracking_stage = 0;

        $relevance = 70;
        $force = false;

        $block = array();
        $block_size = 1000;

        $keywords_force = "listing, supported, freeze, froze, hack, hacked, collaborate, interruption, interrupted, DDoS, delisting, delist, SEC, subpoena, partner, partnership, partnering, announce, announcing, announcement, maintenance, custody, outage,TFSA,Mutual Fund";

        $keywords_force_alert = "hack, hacked, subpoena,TFSA";


        $rest_time = 60 * 5;
        $newArray = array_keys($block);
        $feedsList = array();
        $throlled = array();

        // $sources_aux = \App\Models\Source::whereNotNull("rss")->where("auto_added", 0)->where("block", 0)->orderBy("name")->get();
        // $sources_aux = \App\Models\Source::whereNotNull("rss")->where("auto_added", 0)->where("block", 0)->orderBy("name")->get();
        $sources_aux = \App\Models\SourceService::leftJoin("sources","source_id","sources.id")->whereNotNull("rss")->orderBy("auto_added","ASC")->orderBy("name")->chunk(100,function($sub) use(&$feedsList){

        // $sources_aux = \App\Models\Source::whereNotNull("rss")->where("auto_added", 0)->orderBy("auto_added","ASC")->orderBy("name")->chunk(100,function($sub) use(&$feedsList){
            foreach ($sub as $sou) {
                $array = array();
                $array["name"] = $sou->name;
                $array["rss"] = $sou->rss;
                $array["category"] = $sou->category;
                $array["sub_category"] = $sou->sub_category;
                $array["subtopic"] = $sou->subtopic;
                $array["role"] = $sou->role;
                $array["importance"] = $sou->importance;
                $array["relevance"] = $sou->relevance;
                $array["instrument_group_id"] = $sou->instrument_group_id;
                if($sou->content_type == ""){
                    $array["content_type"] = "crypto";
                } else {
                    $array["content_type"] = $sou->content_type;
                }

                // array_push($block, $array);
                array_push($feedsList, $array);
                // print_r(count($feedsList)." ");
            }
        });




        $rest_time = 60 * 5;
        $newArray = array_keys($block);

        // for ($x = $this->argument('block') * $block_size; $x <= $this->argument('block') * $block_size + $block_size; $x++) {
        //     // if($x < count($newArray)) array_push($feedsList,$block[$newArray[$x]]);
        //     if ($x < count($newArray)) $feedsList[$newArray[$x]] = $block[$newArray[$x]];
        // }

        print_r("Scanning over: ".count($feedsList)." websites");



        $engine = "main";
        $lastDateArray = array();
        $links = array();
        $contents = array();
        $counters = array();
        $lastDate = null;
        $currentDate = null;
        $lastDate = null;


        foreach ($feedsList as $feedL => $val) {

            $headlines = \App\Models\Headline::where("author", $val["name"])->where("source", "rss")->orderBy("created_at", "DESC")->take(1000)->get();
            $links[$feedL] = array();
            $contents[$feedL] = array();
            $lastDateArray[$feedL] = "";
            $author = ucfirst($val["name"]);

            foreach ($headlines as $headline) {

                array_unshift($links[$feedL], $headline->link);
                array_unshift($contents[$feedL], $headline->text);
                $currentDate = strToTime($headline->created_at);

                if ($currentDate >= $lastDate or $lastDate == "") {
                    $lastDate = $currentDate;
                    $lastDateArray[$feedL] = $lastDate;
                }
            }
        }


        while (true) {



            foreach ($feedsList as $key => $val) {

                try {
                    $options_proxies = array();
                    $proxy = $this->proxies_list[array_rand($this->proxies_list)];
                    $options_proxies['curl.options'] = [CURLOPT_PROXY => $proxy];
                    $rss = Feeds::make($val["rss"], 0,true,$options_proxies);

                    $feeds = $rss->get_items();
                    print_r("Count " . count($feeds) . "\n");

                    if (count($feeds) == 0) {

                        // $pause = time() + $rest_time;
                        // if (array_key_exists("throttle", $val)) {
                        //     $pause = time() + ($rest_time * config("constants.throttle_rss"));
                        // }
                        // $feedsList[$key]["throttle"] = $pause;

                        print_r("THROLLED\n\n");
                        print_r("We have been throlled by " . $rss->feed_url);

                        logger()->warning("We have been throlled by " . $rss->feed_url);
                        continue;
                    }

                    if (!array_key_exists($key, $links)) $links[$key] = array();
                    if (!array_key_exists($key, $contents)) $contents[$key] = array();
                    if (!array_key_exists($key, $counters)) $counters[$key] = 0;





                    foreach ($feeds as $feed) {
                        $this->find_image($feed);

                        $author_object = null;
                        $importance_fs_ai = "normal";
                        $check_context = false;
                        $importance_fs_ai_number = -1;
                        $context_tagged_string = "";

                        try {

                            sleep(1);

                            if (in_array($feed->get_permalink(), $links[$key]) or in_array($feed->get_title(), $contents[$key])) {
                                // print_r("Skipping: ");


                                // print_r($feed->get_permalink());

                                // print_r($feed->get_title());

                                // print_r("\n\n");

                                // logger()->error("Skipping: ");
                                // logger()->error($val["rss"]);
                             } else {
                                $link = "";

                                try {
                                    $new_author = $val["name"];
                                    $author_host = $val["rss"];



                                    if (!array_key_exists(strtolower(\App\Libraries\Helper::sanitizeText($new_author)), $sources)) {

                                        $author_object = new \App\Models\Source;
                                        $author_object->name = ucfirst(\App\Libraries\Helper::sanitizeText($new_author));
                                        $author_object->category = "news";
                                        $author_object->sub_category = "market news";
                                        $author_object->url = $author_host;
                                        $author_object->rss = $author_host . "/feed";
                                        $author_object->instrument_group_id = (array_key_exists("instrument_group_id", $val)) ? $val["instrument_group_id"] : null;
                                        $author_object->subtopic = (array_key_exists("subtopic", $val)) ? $val["subtopic"] : null;
                                        $author_object->importance = "normal";
                                        $author_object->relevance = 65;
                                        $author_object->auto_added = 1;
                                        $author_object->save();

                                        $sources[strtolower($author_object->name)] = $author_object;

                                        $author = $author_object->name;
                                        $instrument_id = $author_object->instrument_id;
                                        $subtopic = (array_key_exists("subtopic", $val)) ? $val["subtopic"] : null;
                                    } else {
                                        $author = ucfirst($new_author);
                                        $instrument_group_id = $sources[strtolower($author)]->instrument_group_id;
                                        $subtopic = $sources[strtolower($author)]->subtopic;
                                        $author_object = $sources[strtolower($new_author)];
                                    }
                                } catch (\Exception $e) {

                                    logger()->error("Failed to find an author for: ");
                                    logger()->error($new_author);
                                    $author = (array_key_exists("name", $val) ? $val["name"] : config("constants.scraping_author"));
                                }
                                // }

                                if (!$author_object) {
                                    if (array_key_exists($val["name"], $sources)) {
                                        $author_object = $sources[$val["name"]];
                                    } elseif (array_key_exists($val["rss"], $sources)) {
                                        $author_object = $sources[$val["rss"]];
                                    } else {
                                        // logger()->error("RSS sources not localized");
                                        continue;
                                    }
                                }

                                $author_id = $sources[strtolower($author)]->id;

                                // print_r($feed->get_permalink());
                                // print_r("\n");


                                //BLOCK any block authors
                                if ((string)$author_object->block != "1") {

                                    $sub_category = $sources[strtolower($author)]->sub_category;
                                    $instrument_group_id = $sources[strtolower($author)]->instrument_group_id;
                                    $subtopic = $sources[strtolower($author)]->subtopic;
                                    $trigger = "";
                                    $text = $feed->get_title();
                                    $image_path = $this->find_image($feed);
                                    $thumb_path = $image_path;
                                    $text = strip_tags($text);
                                    $text = \App\Libraries\Helper::cleanHeadline($text);
                                    $link = ($link == "") ? $feed->get_permalink() : $link;
                                    $posted_at = $feed->get_date('Y-m-d H:i:s');
                                    $currentDate = strToTime($posted_at);
                                    $now = strtotime("now");
                                    $paid = true;
                                    $link = html_entity_decode($link);
                                    $channel = "headlines.rss";
                                    $algo = array();
                                    $relevance = $sources[strtolower($author)]->relevance;

                                    //Deal with sources with certain cases ----------------------------------------------------------------
                                    if ($currentDate > $now || $posted_at == "") {
                                        $currentDate = $now;
                                        $posted_at = Date('Y-m-d H:i:s');
                                    }

                                    if ($sources[strtolower($author)]->category != "") {
                                        $category = $sources[strtolower($author)]->category;
                                    } else {
                                        $category = "news";
                                    }

                                    if ($sources[strtolower($author)]->sub_category != "") {
                                        $sub_category = $sources[strtolower($author)]->sub_category;
                                    } else {
                                        $sub_category = "misc";
                                    }

                                    //CONSTRUCT HEADLINE -------------------------------------------------------------
                                    $new_headline = null;
                                    $new_headline = new \App\Models\Headline;

                                    $new_headline->author = $author;
                                    $new_headline->link = $link;
                                    $new_headline->text = $text;

                                    $context = true;

                                    if($author_object->content_type != "" and stripos($author_object->content_type,"general") !== false){
                                        $context = $new_headline->context_detect();
                                    }


                                    //CHECK FOR EXISITNG IN DB HEADLINE ----------------------------------------------------------------
                                    $headline = \App\Models\Headline::where("text", $text)->where(function ($query2) use ($author, $author_id) {
                                        $query2->orWhere("author", $author);
                                        $query2->orWhere("author_id", $author_id);
                                    })->where("source", "rss")->where("created_at", ">=", Carbon::now()->subDays(14))->first();


                                    if (!$headline and $context) {

                                        //CHRIS AI ALGORITHMIC
                                        if ($sources[strtolower($author)]->importance != "critical") {

                                            $local_importance = "";

                                            $trigger = \App\Libraries\Helper::match(strtolower($keywords_force), $text);
                                            if ($trigger != "") {
                                                $importance = "critical";
                                                $local_importance = "critical";
                                                $context_tagged_string = $trigger;
                                            }
                                            $trigger = \App\Libraries\Helper::match(strtolower($keywords_force_alert), $text);
                                            if ($trigger != "") {
                                                $importance = "critical";
                                                $local_importance = "critical";
                                                $context_tagged_string = $trigger;
                                            }

                                            if($local_importance == ""){
                                                $importance = $sources[strtolower($author)]->importance;
                                            }

                                        } else {
                                            $importance_fs_ai = "critical";
                                            $importance_fs_ai_number = 3;
                                            array_push($algo, "manual_override");
                                        }


                                        if($image_path != ""){
                                            $new_headline->image_path = $image_path;
                                            $new_headline->thumb_path = $thumb_path;
                                        }
                                        $new_headline->topic = $this->find_category($feed);
                                        $new_headline->proxy_used = $proxy;
                                        $new_headline->source = "rss";
                                        $new_headline->importance = $importance_fs_ai;
                                        $new_headline->rating3 = ($importance_fs_ai_number == -1 && $val["importance"] == "critical" ? 3 : $importance_fs_ai_number);
                                        $new_headline->algo = implode(",", $algo);
                                        $new_headline->category = $category;
                                        $new_headline->keywords = $context_tagged_string;
                                        $new_headline->author_id = $sources[strtolower($author)]->id;
                                        $new_headline->sub_category = $sub_category;
                                        $new_headline->duplicate = 0;
                                        $new_headline->response = $importance_fs_ai_number;
                                        $new_headline->relevance = $relevance;
                                        $new_headline->channel = $channel;
                                        $new_headline->content = strip_tags($feed->get_description());

                                        if ($posted_at == "") {
                                            $posted_at = Date('Y-m-d H:i:s');
                                        }

                                        $new_headline->posted_at = $posted_at;


                                        if ($new_headline->is_garbage()) {
                                            array_push($algo, "HASH_AT_WEIGHT");
                                            $object_garbage = new \App\Models\HeadlineGarbage($new_headline->toArray());
                                            $object_garbage->algo = implode(",", $algo);
                                            $object_garbage->save();
                                        } else {
                                            try {

                                                $context_array = explode(",", $sources[strtolower($author)]->content_type);
                                                if (in_array("crypto", $context_array)) {
                                                    $check_context = true;
                                                }


                                                print_r("Headline Added: ". $new_headline->text."\n");
                                                $new_headline->save();
                                                // print_r($new_headline);
                                                // exit(0);
                                                if($author_object){
                                                    $author_object->news_scraped += 1;
                                                    $author_object->save();
                                                }

                                                print_r("SUCCESS headline ".$new_headline->text."\n\n");
                                                // $new_headline->affects_compute(null, false, array($instrument_group_id), array($subtopic), false);
                                                $new_headline->affects_compute(false);
                                                $new_headline->tag_detect();

                                                if($image_path == ""){
                                                    ProcessFeaturedImage::dispatch($new_headline);
                                                }

                                                $author_object->increment('news_scraped');
                                                $author_object->last_time_scraped = Date('Y-m-d H:i:s');
                                                $author_object->save();






                                            } catch (\Exception $e) {


                                                $new_headline->save();

                                                logger()->error($e);

                                            }

                                            $lastDateArray[$key] = $currentDate;
                                        }

                                        $links[$key][$counters[$key]] = $link;
                                        $contents[$key][$counters[$key]] = $text;
                                        $counters[$key] = $counters[$key] + 1;
                                    }

                                    if (count($links[$key]) >= 1000) {
                                        $counters[$key] = 0;
                                    }
                                }
                            }
                        } catch (\Exception $e) {
                            logger()->error("\n\n");
                            logger()->error($e);
                        }
                    }
                } catch (\Exception $e) {
                    logger()->error("\n\n");
                    logger()->error($e);
                }
            }
            sleep(config("constants.rss_rate"));
        }
    }
}
