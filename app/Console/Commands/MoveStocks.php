<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MoveStocks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'move:stocks';

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
        $instruments = \App\Models\InstrumentGroup::all();
        foreach($instruments as $instrument){
            $stock = \App\Models\Stock::where("ticker",$instrument->name)->first();
            if(!$stock){
                $stock = new \App\Models\Stock;
                $stock->ticker = $instrument->name;
                $stock->name = $instrument->context_name;
                $stock->active = 0;
                $stock->instrument_group_id = $instrument->id;

            } else {
                $stock->instrument_group_id = $instrument->id;
            }
            $stock->save();
        }
    }
}
