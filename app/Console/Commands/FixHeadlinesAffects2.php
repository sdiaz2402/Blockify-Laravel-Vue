<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FixHeadlinesAffects2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fixes:headlines_affects_v2 {param1?}';

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


        $objects = \App\Models\Headline::where("subtopic", "like", "%Ticker%")->get();

        foreach ($objects as $object) {

            $sub = $object->subtopic;
            $sub_array = explode(",", $sub);
            $output = array();
            $output_ids = array();

            foreach ($sub_array as $item) {
                if ($item == "Ticker") {
                } else {
                    array_push($output, $item);
                }
            }

            foreach ($sub_array as $item) {
                if ($item == 5699) {
                } else {
                    array_push($output_ids, $item);
                }
            }

            $final_subtopic = implode(",", $output);
            $final_affects = implode(",", $output_ids);

            $object->subtopic = $final_subtopic;
            $object->affects = $final_affects;
            $object->save();
        }
    }
}
