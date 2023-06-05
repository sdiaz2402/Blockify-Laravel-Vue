<?php

namespace App\Console\Commands;

use App\Jobs\ProcessFeaturedImage;
use Illuminate\Console\Command;

class TestFeaturedImage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Test:FeaturedImage {headline?}';

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
        $headlines = array();

        if($this->argument("headline") == ""){
            $headlines = \App\Models\Headline::whereNull("image_path")->orWhere("image_path","=","")->orderBy("id","desc")->limit(1000)->get();
        } else {
            $headlines = \App\Models\Headline::where("id",$this->argument("headline"))->orderBy("id","desc")->limit(1000)->get();
        }

        foreach($headlines as $headline){
            ProcessFeaturedImage::dispatch($headline);
        }

    }
}
