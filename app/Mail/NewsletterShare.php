<?php

namespace App\Mail;

use App\Http\Controllers\WatchlistController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewsletterShare extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $user = 0;
    public $subject = 0;
    public $news = 0;
    public $text = 0;
    public $content = 0;
    public $subtopic = 0;
    public $link = 0;


    public function __construct($user,$subject,$text,$content,$subtopic,$link)
    {
        //
        $this->user = $user;
        $this->subject = $subject;
        $this->text = $text;
        $this->content = $content;
        $this->subtopic = $subtopic;
        $this->link = $link;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return  $this->subject($this->subject)
                        ->view('emails.share')
                        ->with(array(
                            "text"=>$this->news,
                            "content"=>$this->content,
                            "subtopic"=>$this->subtopic,
                            "link"=>$this->link,
                        ));
    }
}
