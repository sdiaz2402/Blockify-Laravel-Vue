<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FixImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:images';

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

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $objects = \App\Models\Source::whereNotNull("logo")->orderBy("id","desc")->get();

        foreach($objects as $object){
            if(\App\Libraries\Helper::match(array("http://"),$object->logo)){
                print_r($object->logo);
                $object->logo = str_replace("http://","https://",$object->logo);
                if($object->logo == "http://"){
                    $object->logo = null;
                }
                print_r("\n");
                $object->save();
            }
        }
    }
}
