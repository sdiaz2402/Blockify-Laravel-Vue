<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FixDuplicates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fixes:headlines_duplicates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Broadcast Article';

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
     * @return mixed
     */
    public function handle()
    {
        $array_to_remove = array();
        \App\Models\Headline::orderBy("id","ASC")->chunk(5000, function ($objs) use (&$array_to_remove) {
            foreach ($objs as $object) {
                // print_r("Checking for: ".$object->text." \n");
                \App\Models\Headline::where("text", $object->text)->where("id", "!=", $object->id)->where("id", "<", $object->id)->where("text","not like","Liquidated%")->chunk(5000, function ($remove) use (&$array_to_remove) {
                    foreach ($remove as $rem) {
                        array_push($array_to_remove, $rem);
                        // print_r("Delete :".$rem->id." ".$rem->text."\n");
                        // print_r(count($array_to_remove)."\n");
                        if(count($array_to_remove) > 200){
                            foreach($array_to_remove as $obj){
                                // print_r("Deleting :".$obj->id." ".$obj->text."\n");
                                $obj->forceDelete();
                            }
                            $array_to_remove = array();
                            empty($array_to_remove);
                            // print_r(count($array_to_remove));
                        }
                    }
                });
            }
        });


        foreach($array_to_remove as $obj){
            $obj->forceDelete();
        }




    }
}
