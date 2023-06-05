<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SyncStocksDaily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:stocks_daily';

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
        try{

            exec("/usr/bin/python3 ".base_path()."/app/Libraries/python/stocks.py");

        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

        try{

            exec("/usr/local/bin/python3 ".base_path()."/app/Libraries/python/stocks_daily.py");

        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

    }
}
