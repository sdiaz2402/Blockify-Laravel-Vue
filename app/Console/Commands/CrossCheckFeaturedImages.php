<?php

namespace App\Console\Commands;

use App\Jobs\ProcessFeaturedImage;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CrossCheckFeaturedImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Integrity:FeaturedImage';

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

        $headlines = \App\Models\Headline::whereNull("image_path")->orWhere("image_path","=","")->where("created_at",">=",Carbon::now()->subDays(15))->orderBy("id","desc")->limit(100)->get();


        foreach($headlines as $headline){
            ProcessFeaturedImage::dispatch($headline);
        }

    }
}
