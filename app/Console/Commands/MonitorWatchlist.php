<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Mail\Alert;
use Illuminate\Support\Facades\Mail;
use DB;


class MonitorWatchlist extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor:wathclist';

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
        $number = 0.03;
        DB::enableQueryLog();
        $watchlist = \App\Models\Watchlist::select("stocks.last","stocks.open","stocks.change","stocks.high","stocks.low","stocks.open","watchlists.*")->leftJoin("stocks","watchlists.instrument_group_id","stocks.instrument_group_id")
        // ->where("favorite",1)
        ->where(function($query){
            $query->whereNull("alerted");
            $query->orWhere("alerted","<=",Carbon::now()->subHours(12));
        });
        // $watchlist->where(function($query) use($number){
        //     // $query->orWhere("stocks.change",">",$number);
        //     $neg = $number*-1;
        //     $query->orWhere("stocks.change","<",$neg);
        // });

        // $neg = $number*-1;
        // $watchlist->where("stocks.change",">",-0.03);

        $watchlist->where("user_id",1);

        $watchlist = $watchlist->get();



        foreach($watchlist as $object){
            $user_id = $object->user_id;
            $ticker = $object->ticker;
            $last_read =  $object->last_id_read;
            $change = $object->change;

            if($object->change > $number or $object->change < ($number*-1)){
                print_r($object->change." ".$object->name."\n");
                $user = \App\Models\User::find($user_id);
                if($user->email == "luisczul@gmail.com"){
                    // print_r($user);
                    // \App\Models\Watchlist::where("instrument_group_id",$object->instrument_group_id)->update(array("alerted"=>Carbon::now()));
                    // Mail::to($user)->send(new Alert($user->id,$ticker,$last_read));
                }


            }

        }



    }
}
