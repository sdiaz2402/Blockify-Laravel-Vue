<?php

namespace App\Console\Commands;

use App\Mail\Alert;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:alert {user} {ticker} {last_read}';

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
        $user_id = $this->argument('user');
        $ticker = $this->argument('ticker');
        $last_read = $this->argument('last_read');

        $user = \App\Models\User::find($user_id);

        Mail::to($user)->send(new Alert($user->id,$ticker,$last_read));


    }
}
