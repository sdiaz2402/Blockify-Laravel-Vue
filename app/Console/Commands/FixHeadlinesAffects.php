<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FixHeadlinesAffects extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fixes:headlines_affects {param1?}';

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
        // print("why");

        $id  = $this->argument('param1');
        // print("Diego");
        if($id){
            $object = \App\Models\Headline::find($id);

            $object->affects_compute();
        } else {
            $objects = \App\Models\Headline::get();

            foreach($objects as $object){
                $object->affects_compute();
            }
        }



    }
}
