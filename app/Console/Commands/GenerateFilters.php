<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateFilters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Generate:Filters';

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
        $objects = \App\Models\InstrumentGroup::get();
        foreach($objects as $object){
            $sub = \App\Models\InstrumentgroupsFilter::where("instrument_group_id",$object->id)->first();
            if(!$sub){

                $sub = new \App\Models\InstrumentgroupsFilter;

            }

            $keywords = array();
            $keywords[] = $object->context_name;
            $sub->instrument_group_id = $object->id;
            $sub->keywords = implode(",",$keywords);
            $sub->core = 1;
            $sub->save();
        }
    }
}
