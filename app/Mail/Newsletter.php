<?php

namespace App\Mail;

use App\Http\Controllers\WatchlistController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Newsletter extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $user = 0;

    public function __construct($user,$subject)
    {
        //
        $this->user = $user;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $controller = new WatchlistController;
        $objects = $controller->list_render($this->user);
        $objects_favorites = $controller->list_render_favorites($this->user);
        $objects_unread = $controller->unread_render($this->user);
        if($this->subject == "mid"){
            $subject = date("D j M")." - Mid Session";
        } else {
            $subject = date("D j M")." - Close Session";
        }
        return  $this->subject($subject)
                        ->view('emails.newsletter')
                        ->with(array(
                            "watchlist"=>$objects,
                            "favorites"=>$objects_favorites,
                            "watchlist_unread"=>$objects_unread
                        ));
    }
}
