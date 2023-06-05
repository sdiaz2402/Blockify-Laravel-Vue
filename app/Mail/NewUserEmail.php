<?php

namespace App\Mail;

use App\Http\Controllers\WatchlistController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewUserEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $user_id = 0;
    public $time = 0;


    public function __construct($user_id,$time)
    {
        //
        $this->user_id = $user_id;
        $this->time = $time;

    }

    /**
     * Build the message.
     *
     * @return $thistest
     */
    public function build()
    {

        $subject = "New StocksNews.app User";
        $user = \App\Models\User::find($this->user_id);

        return  $this->subject($subject)
                        ->view('emails.new_user')
                        ->with(array(
                            "time"=>$this->time,
                            "user"=>$user
                        ));
    }
}
