<?php

namespace App\Console\Commands;

use App\Models\InstrumentGroup;
use App\Models\Stock;
use App\Models\Watchlist;
use Illuminate\Console\Command;
use DB;

class TrimTickers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:fix_tickers';

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
        $stocks = InstrumentGroup::get();
        foreach($stocks as $stock){
            $name = $stock->name;
            $name = trim($name);
            $name = strtoupper($name);
            $stock->name = $name;
            $stock->save();
        }

        $stocks = Stock::get();
        foreach($stocks as $stock){
            $name = $stock->ticker;
            $name = trim($name);
            $name = strtoupper($name);
            $stock->ticker = $name;
            $stock->save();
        }

        $stocks = Watchlist::get();
        foreach($stocks as $stock){
            $name = $stock->ticker;
            $name = trim($name);
            $name = strtoupper($name);
            $stock->ticker = $name;
            $stock->save();
        }
    }
}
