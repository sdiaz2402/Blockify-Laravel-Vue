<?php

namespace App\Console\Commands;

use App\Jobs\ProcessFeaturedImage;
use Illuminate\Console\Command;

class TestAllImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Test:Images {headline?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    function checkRemoteFile($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        // don't download content
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);
        curl_close($ch);
        if($result !== FALSE)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $headlines = array();

        // if($this->argument("headline") == ""){
        //     $objects = \App\Models\Source::whereNotNull("logo")->orderBy("id","desc")->get();
        // } else {
        //     $objects = \App\Models\Source::where("id",$this->argument("headline"))->orderBy("id","desc")->get();
        // }

        // foreach($objects as $object){
        //     print_r($object->name);
        //     $result = $this->checkRemoteFile($object->logo);

        //     if(!$result){
        //         print(" Bad");
        //         $object->logo = null;
        //         $object->save();
        //     } else {
        //         print(" Good");
        //     }
        //     print_r("\n");
        // }

        if($this->argument("headline") == ""){
            $headlines = \App\Models\Headline::whereNull("image_path")->orWhere("image_path","=","")->orderBy("id","desc")->limit(100)->get();
        } else {
            $headlines = \App\Models\Headline::where("id",$this->argument("headline"))->orderBy("id","desc")->limit(1000)->get();
        }

        foreach($headlines as $headline){
            $result = $this->checkRemoteFile($headline->image_path);
            if(!$result){
                $headline->image_path = null;
                $headline->save();
            }
        }



    }
}
