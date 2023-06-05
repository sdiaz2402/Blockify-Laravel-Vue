<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

class ProcessNewNewsletter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        try{

            logger()->error("COINMARKETCAP:GetCryptoPrices ".time());
            $exit_code = Artisan::call("COINMARKETCAP:GetCryptoPrices");

        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

        try{
            logger()->error("metrics:today ".time());
            $exit_code = Artisan::call("metrics:today");

        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

        try{
            logger()->error("levels:recalculate ".time());
            $exit_code = Artisan::call("levels:recalculate");

        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

        try{
            logger()->error("sync:stocks ".time());
            $exit_code = Artisan::call("sync:stocks");

        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

        try{
            logger()->error("nav:premium ".time());
            // $exit_code = Artisan::call("Scrape:CME ".time());
            $exit_code = Artisan::call("nav:premium");
            logger()->error("DONE".time());

        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }


        try{
            logger()->error("Scrape:CME ".time());
            // $exit_code = Artisan::call("Scrape:CME ".time());
            $exit_code = Artisan::call("Scrape:CME");
            logger()->error("DONE".time());

        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
}
