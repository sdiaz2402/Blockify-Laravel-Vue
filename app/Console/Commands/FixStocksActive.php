<?php

namespace App\Console\Commands;

use App\Models\InstrumentGroup;
use App\Models\Stock;
use App\Models\Watchlist;
use Illuminate\Console\Command;

class FixStocksActive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:active_stocks';

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
        $watchlists = Watchlist::get();

        foreach($watchlists as $watchlist){

            print_r($watchlist->ticker."\n");
            if($watchlist->ticker == "SOFI"){
                $diego = 0;
            }
            $stock = Stock::where("instrument_group_id",$watchlist->instrument_group_id)->first();
            if(!$stock){
                $instrument_group = InstrumentGroup::where("id",$watchlist->instrument_group_id)->first();
                if($instrument_group){
                    $stock = new \App\Models\Stock;
                    $stock->ticker = $instrument_group->name;
                    $stock->active = 1;
                    $stock->save();
                }

            } else {
                $stock->active = 1;
            }
            $stock->save();
        }
    }
}
