<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckContext extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Check:Context';

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
        // $id = 3362;

        // $author = 21229;

        // $author_object = \App\Models\Source::find($author);


        // if($author_object->content_type != "" and stripos($author_object->content_type,"general") !== false){
        //     print("----");
        // }

        // $headline = \App\Models\Headline::find($id);

        // $result = $headline->context_detect();

        // if($result){
        //     print("YES YES");
        // } else {
        //     print("NO NO");
        // }

        // print_r("Diego");

        // print_r($result);

        $objects = \App\Models\Headline::orderby("id","desc")->get();

        foreach($objects as $object){
            $object->covid_context();
        }
    }
}
