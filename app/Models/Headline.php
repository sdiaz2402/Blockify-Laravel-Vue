<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Log;
use Illuminate\Database\Eloquent\SoftDeletes;

use function PHPSTORM_META\map;

class Headline extends Model
{
    //
    use SoftDeletes;
    // protected $dateFormat = 'U';

    protected $columns = [];

    public function __construct()
    {
        parent::__construct();

    }

    function AI_duplicate_system($new_headline, $old_headline, $treshold, $type = "headlines")
    {

        if ($type == "headlines") {
            $ratio = 0;
            $match_words = 0;
            $unmatched_words = 0;

            $new_headline = preg_replace("#[[:punct:]]#", "", $new_headline);
            $old_headline = preg_replace("#[[:punct:]]#", "", $old_headline);
            $explode_new_headline = explode(" ", $new_headline);
            $explode_old_headline = explode(" ", $old_headline);
            $explode_new_headline_stemmed = array();
            $explode_old_headline_stemmed = array();
            foreach ($explode_new_headline as $word) {
                // $stem = \Porter::Stem($word);
                // array_push($explode_new_headline_stemmed, $stem);

                // $stem = \Porter::Stem($word);
                array_push($explode_new_headline_stemmed, $word);
            }


            foreach ($explode_old_headline as $word) {
                // $stem = \Porter::Stem($word);
                // array_push($explode_old_headline_stemmed, $stem);

                // $stem = \Porter::Stem($word);
                array_push($explode_old_headline_stemmed, $word);
            }

            $intersec = array_intersect($explode_old_headline_stemmed, $explode_new_headline_stemmed);
            //$diff = array_diff($explode_old_headline_stemmed,$explode_new_headline_stemmed);

            $ratio = count($intersec) / count($explode_new_headline);

            $ratio = $ratio * 100;

            if ($ratio > $treshold) {
                return true;
            }
        } else {
            $new_headline = preg_replace("#[[:punct:]]#", "", $new_headline);
            $old_headline  = preg_replace("#[[:punct:]]#",  "", $old_headline);
            if ($new_headline == $old_headline) {
                return true;
            }
        }

        return false;
    }

    public function affects_compute($instruments = null, $force = true, $affects_array = array(), $subtopics_array = array(), $save = true){
        $affects = array();
        $subtopics = array();
        $crypto = false;
        // print("Diego");

        if($force == false and $this->subtopic != ""){
            $affects = explode(",", $this->subtopic);
        }

        if (!$instruments) $instruments = \App\Models\InstrumentgroupsFilter::with("instrumentGroup")->get();
        $filters = array();
        foreach ($instruments as $instrument) {
            $filters[$instrument->instrument_group_id] = explode(",", $instrument->keywords);

            // print_r($filters);
            foreach ($filters as $ins => $key) {
                foreach ($key as $k) {
                    // if ($k != "" and strpos(strtolower($this->text), trim(strtolower($k))) !== false and !in_array($ins, $affects)) {
                    if ($k != "" and ($this->containsWord($this->text, $k) or $this->containsWord($this->content, $k)) and !in_array($ins, $affects)) {
                        if ($instrument->instrumentGroup and $instrument->instrumentGroup->name != "") {
                            array_push($affects, $ins);
                            array_push($subtopics, $instrument->instrumentGroup->name);
                        } else {
                            Log::error("This headline does not have corresnpoding affects ".$this->id." ".$this->text);
                        }
                    }
                }
            }
        }

        if (count($affects_array) > 0) $affects = array_unique(array_merge($affects, $affects_array), SORT_REGULAR);
        if (count($subtopics_array) > 0) $subtopics = array_unique(array_merge($subtopics, $subtopics_array), SORT_REGULAR);

        $this->affects = implode(",", $affects);
        $this->subtopic = implode(",", $subtopics);
        if ($save) $this->save();
}

    public function tag_detect(){

        $keywords = array(
            "NFT" => "NFTs",
            "Fungible" =>"NFTs",
            "DeFi" => "DeFi"
        );

        $context = false;
        $keyword_found = "";
        foreach($keywords as $key =>$value){
            if(stripos($this->content,$key) !== false or stripos($this->text,$key) !== false){
                $context = true;
                $keyword_found = $value;
                if(\App\Models\HeadlineContext::where("headline_id",$this->id)->where("context",$value)->count() == 0){
                    $cont = new \App\Models\HeadlineContext();
                    $cont->headline_id = $this->id;
                    $cont->context = $value;
                    $cont->save();
                }

            }
        }



        return $context;

    }

    public function context_detect(){

        $keywords = array(
            "Bitcoin",
            "Digital Asset",
            "Stable Coin",
            "stablecoins",
            "crypto-assets",
            "Crypto Asset",
            "Non-fungible token",
            "Crypto",
            "Cryptocurrency"
        );

        $context = false;
        foreach($keywords as $key){
            if(stripos($this->content,$key) !== false or stripos($this->text,$key) !== false){
                $context = true;
            }
        }

        return $context;

    }

    public function covid_context($save = true){

        $keywords = array(
            "covid",
            "mrna",
            "pandemy",
            "vaccine",
            "corona virus",
            "booster",
            "pfizer",
            "moderna",
            "mrna",
            "merck",
            "antibody",
            "co-19",
        );

        $context = false;
        foreach($keywords as $key){
            if(stripos($this->content,$key) !== false or stripos($this->text,$key) !== false){
                $context = true;
            }
        }
        $this->reject = 1;

        if($context and $save){

            $this->save();
        }


        return;

    }

    public function is_garbage()
    {


        $headline = $this->text;
        # delete healines with a lot of @words
        $percentage_ratio_allowed_at = "0.15";
        $percentage_ratio_allowed_hash = "0.3";
        $current_ratio_at = 0;
        $current_ratio_hash = 0;
        $min_amount_words = 5;


        $words = explode(" ", $headline);
        $at_words = 0;
        $hash_words = 0;
        $normal_words = 0;
        for ($i = 0; $i < count($words); $i++) {

            // logger()->warning($words[$i]);

            if (!empty($words[$i]) and $words[$i][0] === '@') {
                $at_words++;
            }

            if (!empty($words[$i]) and $words[$i][0] === '#') {
                $hash_words++;
            } else {
                if ($words[$i] != "" and $words[$i] != " ") {
                    $normal_words++;
                }
            }
        }
        if ($normal_words == 0) {
            return true;
        }
        $current_ratio_at = $at_words / $normal_words;
        $current_ratio_hash = $hash_words / $normal_words;

        // logger()->warning('AT Ratio');
        // logger()->warning($current_ratio_at);

        // logger()->warning('# Ratio');
        // logger()->warning($current_ratio_hash);

        // logger()->warning('Normal Ratio');
        // logger()->warning($normal_words);



        if ($current_ratio_at >= $percentage_ratio_allowed_at or $current_ratio_hash >= $percentage_ratio_allowed_hash) {
            return true;
        }

        # delete headlines less than 4 words
        if (count($words) <= $min_amount_words) {
            return true;
        }

        return false;
    }

    function categorize_headline()
    {
        $author = \App\Models\Source::where(function ($query) {
            $query->orWhere("name", $this->author);
            $query->orWhere("twitter_handle", $this->author);
        })->first();
        if ($author) {
            $this->category = $author->category;
            $this->sub_category = $author->sub_category;
        } else {
            if ($this->source == "rss" or $this->source == "scraping" or $this->source == "rss") {
                $this->category = "news";
                $this->sub_category = "news source";
            } else {
                $this->category = "social";
                $this->sub_category = "influencer";
            }
        }
        $this->save();
    }

    function containsWord($str, $word)
    {
        $word = trim($word);
        return !!preg_match('#\\b' . preg_quote($word, '#') . '+?(s?)\\b#i', $str);
    }





}
