<?php

namespace App\Mail;

use App\Http\Controllers\WatchlistController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Alert extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $user = 0;
    public $ticker = "";
    public $last_read = 0;

    public function __construct($user,$ticker,$last_read)
    {
        //
        $this->user = $user;
        $this->ticker = $ticker;
        $this->last_read = $last_read;
    }

    /**
     * Build the message.
     *
     * @return $thistest
     */
    public function build()
    {
        $ticker = $this->ticker;
        $instru = \App\Models\Stock::where("ticker",$ticker)->first();

        // $headlines = $objects = \App\Models\Headline::where("id", ">", $this->last_read);
        $headlines = $objects = \App\Models\Headline::select();


        if ($instru) {

            $instruments = array($instru->instrument_group_id);

            $filt = array();
            if (is_array($instruments)) {
                $filt = $instruments;
            } else {
                $filt = explode(",", $instruments);
            }

            if (is_array($filt) and count($filt) > 0) {

                $objects = $objects->where(function ($query) use ($filt) {
                    foreach ($filt as $type) {
                        if ($type == "") {
                            $query->orWhereRaw('FIND_IN_SET(0,affects)');
                        } else {
                            $query->orWhereRaw('FIND_IN_SET(' . $type . ',affects)');
                        }
                    }
                });
            }
        }
        $unread_objects = $objects->orderBy("id","desc")->get();
        $unread = count($unread_objects);
        // $headlines = $objects = \App\Models\Headline::where("id", ">", $this->last_read);
        if($unread > 4){
            $headlines = $objects = \App\Models\Headline::select();


        if ($instru) {

            $instruments = array($instru->instrument_group_id);

            $filt = array();
            if (is_array($instruments)) {
                $filt = $instruments;
            } else {
                $filt = explode(",", $instruments);
            }

            if (is_array($filt) and count($filt) > 0) {

                $objects = $objects->where(function ($query) use ($filt) {
                    foreach ($filt as $type) {
                        if ($type == "") {
                            $query->orWhereRaw('FIND_IN_SET(0,affects)');
                        } else {
                            $query->orWhereRaw('FIND_IN_SET(' . $type . ',affects)');
                        }
                    }
                });
            }
        }
        $unread_objects = $objects->orderBy("id","desc")->get();
        }



        $last_5 = array();

        $counter = 0;
        foreach($unread_objects as $row){
            if($counter >= 5){
                break;
            }

            array_push($last_5,$row);

            $counter = $counter +1;
        }

        $change = $instru->change;
        $change = $change *100;
        $change = number_format($change,2);

        $type_alert = "Bearish";
        $subject = $type_alert." ALERT - ".$this->ticker." moved more than (-".$change."%)";
        if($instru->change > 0){
            $type_alert = "Bullish";
            $subject = $type_alert." ALERT - ".$this->ticker." moved more than +".$change."%";
        }



        return  $this->subject($subject)
                        ->view('emails.alert')
                        ->with(array(
                            "ticker"=>$this->ticker,
                            "unread"=>$unread,
                            "change"=>$change,
                            "last_5"=>$last_5
                        ));
    }
}
