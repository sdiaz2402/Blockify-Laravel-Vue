<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportWatchlistStocks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Import:WatchlistStocks';

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
        $objects = \App\Models\Watchlist::distinct("ticker")->get();

        foreach($objects as $object){

            $check = \App\Models\Stock::where("ticker",$object->ticker)->first();
            if(!$check){
                $new = new \App\Models\Stock;
                $new->ticker = $object->ticker;
                $new->save();
            }

        }
    }
}
