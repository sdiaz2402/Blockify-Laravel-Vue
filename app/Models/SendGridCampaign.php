<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Log;
use Illuminate\Database\Eloquent\SoftDeletes;



class SendGridCampaign extends \App\Models\SendGridAPI
{

    protected $events = null;

    //ATRIBUTES

    public $id = "";
    private $name = "";
    public $status = "";
    public $alerts = false;
    public $categories = "";
    public $send_to = array();
    public $subject = "";
    public $sender_id = "";
    public $design_id = "";
    public $send_at = "";
    public $segment = "";
    public $segment_id = "";
    public $html_content = "";
    public $plain_content = "";
    public $suppression_group_id = "";
    public $generate_plain_content = true;
    public $send_to_all = false;

    function __construct($id = "")
    {

        parent::__construct();

        if ($id != "") {
            $this->get_campaing_by_id($id);
        }
    }

    public function __set($name, $value) {
        if($name == "name"){
            $date = date("Y-m-d");
            $this->$name = $date." - ".substr($value,0,82);
        } else if (false === property_exists(get_class(), $name)) {
            Log::error("SendgridAPICampaign","set","Property does not exist ".$name);
        } else {
            $this->$name = $name;
        }
    }

    public function __get($name) {
        return (isset($this->$name)) ? $this->$name : NULL;

    }

    public function get_campaing_by_id($id)
    {
        try {

            // $this->local_debug($id,"GET CAMPAIGN BY ID");

            $response = $this->client->_("marketing/singlesends/" . $id)->get();

            // print_r($response);

            $response_array = json_decode($response->body(), true);


            if (in_array($response->statusCode(),$this->CODE_SUCCESS)) {


            $this->id = $response_array["id"];
            $this->name = $response_array["name"];
            $this->status = $response_array["status"];
            $this->categories = $response_array["categories"];
            $this->send_at = $response_array["send_at"];
            $this->segment_id = $response_array["send_to"]["segment_ids"];
            $this->subject = $response_array["email_config"]["subject"];
            $this->html_content = $response_array["email_config"]["html_content"];
            $this->plain_content = $response_array["email_config"]["plain_content"];
            $this->suppression_group_id = $response_array["email_config"]["suppression_group_id"];
            $this->generate_plain_content = $response_array["email_config"]["generate_plain_content"];
            $this->sender_id = $response_array["email_config"]["sender_id"];
        }else {
            Log::error("SENDGRID", "get_campaing_by_id", "Error on get campaing id");
            Log::error("SENDGRID", "get_campaing_by_id ID:", $id);

            if (array_key_exists("errors", $response_array)) {
                Log::error("SENDGRID", "get_campaing_by_id", $response_array["errors"]);
            } else {
                Log::error("SENDGRID", "get_campaing_by_id", $response_array);
            }
        }
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
            //LOGGER ERROR REPORT
        }
    }

    public function get_campaing_by_name($name)
    {
        try {


            $query_params = array("name" => $name);

            Log::error("SENDGRID", "get_campaing_by_name", $query_params);

            $response = $this->client->_("marketing/singlesends/search")->post($query_params);

            Log::error("SENDGRID", "get_campaing_by_name", $response);


            $response_array = json_decode($response->body(), true);

            if (in_array($response->statusCode(),$this->CODE_SUCCESS)) {

                if (count($response_array["result"]) > 0) {
                    $this->get_campaing_by_id($response_array["result"][0]["id"]);
                }
            } else {
                Log::error("SENDGRID", "get_campaing_by_name", "Error on get campaing name");

                Log::error("SENDGRID", "get_campaing_by_name params", $query_params);
                if (array_key_exists("errors", $response_array)) {
                    Log::error("SENDGRID", "get_campaing_by_name", $response_array["errors"]);
                } else {
                    Log::error("SENDGRID", "get_campaing_by_name", $response_array);
                }
            }
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
            //LOGGER ERROR REPORT
        }
    }


    public function send($datetime, $now = false)
    {


        try {
            $query_params = array();
            if ($now) {
                $this->send_at = "now";
                $query_params["send_at"] = $this->send_at;
            } else {
                $this->send_at = $datetime;
                $query_params["send_at"] = $datetime;
            }
            $response = $this->client->_("marketing/singlesends/" . $this->id . "/schedule")->put($query_params);

            $response_array = json_decode($response->body(), true);

            if (in_array($response->statusCode(),$this->CODE_SUCCESS)) {

                return true;
            } else {

                Log::error("SENDGRID", "send", "Error on sengrid send");

                if (array_key_exists("errors", $response_array)) {
                    Log::error("SENDGRID", "send", $response_array["errors"]);
                } else {
                    Log::error("SENDGRID", "send", $response_array);
                }
            }

            $this->local_debug($response, "Schedule answer");
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
            //LOGGER ERROR REPORT
        }
    }

    public function cancel()
    {

        try {

            $response = $this->client->_("marketing/singlesends/" . $this->id . "/schedule")->delete();

            $response_array = json_decode($response->body(), true);

            if (in_array($response->statusCode(),$this->CODE_SUCCESS)) {

                return true;
            } else {
                Log::error("SENDGRID", "cancel", "Error on Cancel");

                if (array_key_exists("errors", $response_array)) {
                    Log::error("SENDGRID", "cancel", $response_array["errors"]);
                } else {
                    Log::error("SENDGRID", "cancel", $response_array);
                }
            }
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
            //LOGGER ERROR REPORT
        }
    }

    public function create()
    {

        $this->exeucute_campaing(true);
    }


    public function update()
    {
        $this->exeucute_campaing(false);
    }


    private function exeucute_campaing($new = true)
    {
        $_segments = array();
        $leads = false;
        try {

            if (is_array($this->segment)) {

                foreach ($this->segment as $s) {
                    if(strtolower($s == "leads")){
                        $leads = true;
                    }
                    $segment = $this->get_segment($s);
                    if ($segment and array_key_exists("id", $segment)) {
                        array_push($_segments, $segment["id"]);
                    }
                }
            } else {
                if(strtolower($this->segment == "leads")){
                    $leads = true;
                }
                $segment = $this->get_segment($this->segment);
                if ($segment and array_key_exists("id", $segment)) {
                    $_segments = array($segment["id"]);
                }
            }



            //FOR NOW LEAVE THIS FOR A WHILE

            if (count($_segments) > 0) {


                if (is_string($this->categories)) {
                    $this->categories = array($this->categories);
                }

                $query_params = array();
                $query_params["name"] = $this->name;
                $query_params["categories"] = $this->categories;
                $query_params["send_to"] = array();
                $query_params["send_to"]["segment_ids"] = $_segments;
                if ($this->send_to_all) {
                    $query_params["send_to"]["all"] = true;
                }
                // $query_params["send_to"]["all"] = $all;

                $email_config = array();
                if ($this->alerts) {
                    $email_config["sender_id"] = $this->SENDER_ID_ALERTS;
                } else {
                    $email_config["sender_id"] = $this->SENDER_ID;
                }

                $unsubscribe_group = 0;

                if($this->suppression_group_id == "" or $this->suppression_group_id == 0){
                    if($leads){
                        $unsubscribe_group = $this->UNSUBSCRIBE_GROUP_LEADS;
                    } else {
                        $unsubscribe_group = $this->UNSUBSCRIBE_GROUP_MEMBERS;
                    }
                } else {
                    $unsubscribe_group = $this->suppression_group_id;
                }

                if ($this->design_id == "") {

                    $email_config["subject"] = $this->subject;
                    $email_config["html_content"] = $this->html_content;
                    // $email_config["custom_unsubscribe_url"] = $this->UNSUBSCRIBE_URL;
                    $email_config["suppression_group_id"] = $unsubscribe_group;
                    if ($this->plain_content == "") {
                        $email_config["generate_plain_content"] = true;
                    } else {
                        $email_config["generate_plain_content"] = false;

                        $email_config["html_content"] = $this->html_content;
                        $email_config["plain_content"] = $this->plain_content;
                    }

                    $query_params["email_config"] = $email_config;
                } else {
                    $email_config["design_id"] = $this->design_id;
                    // $email_config["custom_unsubscribe_url"] = $this->UNSUBSCRIBE_URL;
                    $email_config["suppression_group_id"] = $unsubscribe_group;
                    $query_params["email_config"] = $email_config;
                }


                //  $this->local_debug(json_encode($query_params), "Query Params Segments Available");

                if ($this->id == "") {

                    $response = $this->client->_("marketing/singlesends")->post($query_params);

                    $response_array = json_decode($response->body(), true);


                    if (in_array($response->statusCode(),$this->CODE_SUCCESS)) {
                        // $this->local_debug($response, "Response from SingleSend");


                        $this->id = $response_array["id"];
                    } else {
                        $this->id = "";
                        Log::error("SENDGRID", "exeucute_campaing", "something went wrong when creating campaing. Following with reponse");

                        if (array_key_exists("errors", $response_array)) {
                            Log::error("SENDGRID", "exeucute_campaing1", $response_array["errors"]);
                            Log::error("SENDGRID", "exeucute_campaing1", $query_params);
                        } else {
                            Log::error("SENDGRID", "exeucute_campaing2", $response_array);
                            Log::error("SENDGRID", "exeucute_campaing2", $query_params);
                        }
                    }
                } else {
                    $response = $this->client->_("marketing/singlesends/" . $this->id)->patch($query_params);

                    $response_array = json_decode($response->body(), true);


                    if (in_array($response->statusCode(),$this->CODE_SUCCESS)) {
                        // $this->local_debug($response, "Response from SingleSend");
                    } else {
                        Log::error("SENDGRID", "exeucute_campaing", "something went wrong when creating campaing update. Following with reponse");
                        if (array_key_exists("errors", $response_array)) {
                            Log::error("SENDGRID", "exeucute_campaing", $response_array["errors"]);
                        } else {
                            Log::error("SENDGRID", "exeucute_campaing", $response_array);
                        }
                    }
                    // $this->local_debug($response, "Response from SingleSend from a UPDATE ccamping");
                }
            } else {
                Log::error("SENDGRID", "exeucute_campaing", "Segments are empty");
            }
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
            //LOGGER ERROR REPORT
        }
    }

    public function pretty_print_array($arr)
    {
        $retStr = "";
        if (is_array($arr)) {
            foreach ($arr as $key => $val) {
                if (is_array($val)) {
                    $retStr .= '-' . $key . ': ' . $this->pretty_print_array($val) . '\n';
                } else {
                    $retStr .= '-' . $key . ': ' . $val . '\n';
                }
            }
        }
        return $retStr;
    }


    public function get_stats_campaing()
    {

        $objects = null;

        if ($this->id != "") {
            try {
                $response = $this->client->_("marketing/stats/singlesends/" . $this->id)->get();

                $response_array = json_decode($response->body(), true);

                // var_dump($response_array);

                if (in_array($response->statusCode(),$this->CODE_SUCCESS)) {
                    // $this->local_debug($response, "response");
                    $results = json_decode($response->body(), true);

                    $nice_output = array();
                    $nice_output["bounces"] = 0;
                    $nice_output["clicks"] = 0;
                    $nice_output["unique_clicks"] = 0;
                    $nice_output["delivered"] = 0;
                    $nice_output["invalid_emails"] = 0;
                    $nice_output["opens"] = 0;
                    $nice_output["unique_opens"] = 0;
                    $nice_output["spam_reports"] = 0;
                    $nice_output["unsubscribes"] = 0;

                    foreach ($results["results"] as $object) {
                        $nice_output["bounces"] += $object["stats"]["bounces"];
                        $nice_output["clicks"] += $object["stats"]["clicks"];
                        $nice_output["unique_clicks"] += $object["stats"]["unique_clicks"];
                        $nice_output["delivered"] += $object["stats"]["delivered"];
                        $nice_output["invalid_emails"] += $object["stats"]["invalid_emails"];
                        $nice_output["opens"] += $object["stats"]["opens"];
                        $nice_output["unique_opens"] += $object["stats"]["unique_opens"];
                        $nice_output["spam_reports"] += $object["stats"]["spam_reports"];
                        $nice_output["unsubscribes"] += $object["stats"]["unsubscribes"];
                    }
                    return $nice_output;
                } else {
                    Log::error("SENDGRID", "get_stats_campaing", "Error on getting spam reports");

                    if (array_key_exists("errors", $response_array)) {
                        Log::error("SENDGRID", "get_stats_campaing", $response_array["errors"]);
                    } else {
                        Log::error("SENDGRID", "get_stats_campaing", $response_array);
                    }
                }
            } catch (Exception $e) {
                Log::error("SENDGRID", "get_stats_campaing", $e->getMessage());
            }
        } else {
            Log::error("SENDGRID", "get_stats_campaing", "ID is empty");
        }

        return null;
    }
}
