<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Log;
use Illuminate\Database\Eloquent\SoftDeletes;



class SendGridAPI extends \SendGrid
{

    protected $events = null;

    //ATRIBUTES

    private $API_KEY = "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";

    protected $CODE_SUCCESS = array(200, 201, 202, 204);
    protected $CODE_ERROR = 500;
    protected $LOCAL_DEBUG_FLAG = false;
    protected $SENDER_ID = 929225;
    protected $SENDER_ID_ALERTS = 1118996;
    protected $UNSUBSCRIBE_GROUP = 15814;
    protected $UNSUBSCRIBE_GROUP_LEADS = 16035;
    protected $UNSUBSCRIBE_GROUP_MEMBERS = 16036;
    protected $DEFAULT_SENDER = "inquiry@fsinsight.com";
    protected $DEFAULT_SENDER_NAME = "FSInsight";
    protected $UNSUBSCRIBE_URL = "https://fsinsight.com/account-settings/";

    function __construct()
    {

        parent::__construct($this->API_KEY);
    }


    public function local_debug($message, $title = "")
    {

        if ($this->LOCAL_DEBUG_FLAG) {
            echo ("--- " . $title . " ---<br/>");

            if (is_object($message)) {
                var_dump($message);
            } else if (is_array($message)) {
                print_r($message);
            } else {
                echo ($message);
            }
            echo ("<br/>");
            echo ("<br/>");


        }

        // if (is_object($message)) {
        //     Log::error("SENDGRID", $title, $message, json_encode($message));
        //     var_dump($message);
        // } else if (is_array($message)) {
        //     Log::error("SENDGRID", $title, $message, print_r($message, true));
        // } else {
        //     Log::error("SENDGRID", $title, $message, print_r($message));
        // }
    }

    public function send_signle_mail($email, $subject, $content, $html = true)
    {

        $tos = array();
        if (is_array($email)) {
            $tos = $email;
        } else {
            $tos = array($email);
        }

        $final_to = array();
        foreach ($tos as $t) {
            array_push($final_to, array("email" => $t));
        }


        try {
            $query_params = array();

            $query_params["personalizations"] = array(array(
                "from" => array(
                    "name" => $this->DEFAULT_SENDER_NAME,
                    "email" => $this->DEFAULT_SENDER
                ),
                "to" => $final_to
            ));
            $query_params["from"] = array(
                "name" => $this->DEFAULT_SENDER_NAME,
                "email" => $this->DEFAULT_SENDER
            );
            $query_params["reply_to"] = array(
                "name" => $this->DEFAULT_SENDER_NAME,
                "email" => $this->DEFAULT_SENDER
            );
            $query_params["subject"] = $subject;

            $query_params["content"] = array();

            if ($html) {
                $sub_content = array(
                    "type" => "text/html",
                    "value" => $content
                );
                array_push($query_params["content"], $sub_content);
            } else {
                $sub_content = array(
                    "type" => "text/plain",
                    "value" => $content
                );
                array_push($query_params["content"], $sub_content);
            }

            $query_params["tracking_settings"] = array(
                "click_tracking" => array(
                    "enable" => true
                ),
                "open_tracking" => array(
                    "enable" => true
                )
            );


            $response = $this->client->_("mail/send")->post($query_params);
            $this->local_debug($response, "Response from single Send");
            // $this->local_debug($response, "Add User Metadata");
            if (in_array($response->statusCode(), $this->CODE_SUCCESS)) {
                return true;
            } else {
                Log::error("SENDGRID", "send_signle_mail", "Error on sending single email");
                $response_array = json_decode($response->body(), true);
                if (array_key_exists("errors", $response_array)) {
                    Log::error("SENDGRID", "send_signle_mail", $response_array["errors"]);
                } else {
                    Log::error("SENDGRID", "send_signle_mail", $response_array);
                }

                return false;
            }
        } catch (Exception $e) {
            Log::error("SENDGRID", "send_signle_mail", $e->getMessage());
        }
    }


    private function add_user_metadata($prop, $type = "Text")
    {

        $code = $this->CODE_ERROR;

        $request_body = array();
        $request_body["name"] = $prop;
        $request_body["field_type"] = $type;

        try {
            $this->local_debug($request_body, "Add User Metadata POST");
            $response = $this->client->_("marketing/field_definitions")->post($request_body);
            $this->local_debug($response, "Add User Metadata");
            if (in_array($response->statusCode(), $this->CODE_SUCCESS)) {
                return true;
            } else {
                Log::error("SENDGRID", "add_user_metadata", "Error on user metadata response");
                Log::error("SENDGRID", "add_user_metadata", json_encode($response));
                $response_array = json_decode($response->body(), true);
                if (array_key_exists("errors", $response_array)) {
                    Log::error("SENDGRID", "add_user_metadata", $response_array["errors"]);
                } else {
                    Log::error("SENDGRID", "add_user_metadata", $response_array);
                }
                return false;
            }
        } catch (Exception $e) {
            Log::error("SENDGRID", "add_user_metadata", $e->getMessage());
        }

        return false;
    }


    /*
    You will be sending me
    $recipients = array( You are sending me 2 emails to add.
        array(  "email"=>"luisczul@gmail.com",
                "role"=>"FSI_subscriber",
                "etc"=>etc),
        array(  "email"=>"luisczul@gmail.com",
                "role"=>"FSI_subscriber",
                "etc"=>etc)
    )

    recipients = You are sending me 1 email to add.
        array(  "email"=>"luisczul@gmail.com",
                "role"=>"FSI_subscriber",
                "etc"=>etc)
    */

    private function verify_user_to_sendgrid($user_as_array)
    {

        if (array_key_exists("email", $user_as_array) and $user_as_array["email"] != "") {
            return true;
        }
        return false;
    }

    private function get_user_fields()
    {
        $user_properties = array("reserved" => array(), "custom" => array());

        try {

            $user_properties_array = null;

            $response = $this->client->_("marketing/field_definitions")->get();
            // $this->local_debug($response, "Local Debug");
            if (in_array($response->statusCode(), $this->CODE_SUCCESS)) {
                $user_properties_array = json_decode($response->body(), true);
                // $this->local_debug($response,"Local Debug");

            } else {
                Log::error("SENDGRID", "get_user_fields", "Error on user metadata response");
                $response_array = json_decode($response->body(), true);
                if (array_key_exists("errors", $response_array)) {
                    Log::error("SENDGRID", "get_user_fields", $response_array["errors"]);
                } else {
                    Log::error("SENDGRID", "send_signle_mail", $response_array);
                }
            }



            if (array_key_exists("reserved_fields", $user_properties_array)) {
                foreach ($user_properties_array["reserved_fields"] as $_object) {
                    if (!in_array($_object["name"], $user_properties)) {
                        array_push($user_properties["reserved"], $_object);
                    }
                }
            }

            if (array_key_exists("custom_fields", $user_properties_array)) {
                foreach ($user_properties_array["custom_fields"] as $_object) {
                    if (!in_array($_object["name"], $user_properties)) {
                        array_push($user_properties["custom"], $_object);
                    }
                }
            }
        } catch (Exception $e) {
            Log::error("SENDGRID", "get_user_fields", $e->getMessage());
        }

        return $user_properties;
    }

    private function does_user_exist_in_sengrid($identifier, $by_email_or_id = "email")
    {

        $objects = $this->get_user_in_sengrid($identifier, $by_email_or_id);

        if (count($objects) > 0) {
            return true;
        }

        return false;
    }

    public function delete_contact_by_email($email)
    {


        try {
            $user_to_delete_array = $this->get_user_in_sengrid($email);
            $this->local_debug($user_to_delete_array, "Users to delete deleted");
            if (count($user_to_delete_array) > 0) {
                $ids = array();
                foreach ($user_to_delete_array as $user_to_delete) {
                    array_push($ids, $user_to_delete["id"]);
                }
                // $this->local_debug($ids, "Users to delete Array");
                $ids_string = implode(",", $ids);

                $query_params = array("ids" => $ids);
                // $this->local_debug($query_params, "Users to delete response");
                $response = $this->client->_("marketing/contacts?ids=" . $ids_string)->delete();
                // $this->local_debug($response, "Users to delete response");
                if (in_array($response->statusCode(), $this->CODE_SUCCESS)) {
                    return true;
                } else {
                    Log::error("SENDGRID", "delete_contact_by_email", " Error on user deleting user" . $email);
                    $response_array = json_decode($response->body(), true);
                    if (array_key_exists("errors", $response_array)) {
                        Log::error("SENDGRID", "add_user_metadata", $response_array["errors"]);
                    } else {
                        Log::error("SENDGRID", "add_user_metadata", $response_array);
                    }
                }
            }

            return false;
        } catch (Exception $e) {
            Log::error("SENDGRID", "add_user_metadata", $e->getMessage());
        }
    }

    public function delete_all_bounces()
    {
        $final = true;
        try {
            $query_params = array("delete_all" => true);
            $response = $this->client->_("suppression/bounces")->delete($query_params);
            if (in_array($response->statusCode(), $this->CODE_SUCCESS)) {
            } else {
                $response_array = json_decode($response->body(), true);
                if (array_key_exists("errors", $response_array)) {
                    Log::error("SENDGRID", "delete_all_bounces", $response_array["errors"]);
                } else {
                    Log::error("SENDGRID", "delete_all_bounces", $response_array);
                }
                $final = false;
            }
            // return false;
        } catch (Exception $e) {
            Log::error("SENDGRID", "delete_all_bounces", $e->getMessage());
        }

        try {
            $query_params = array("delete_all" => true);
            $response = $this->client->_("suppression/blocks")->delete($query_params);
            if (in_array($response->statusCode(), $this->CODE_SUCCESS)) {
            } else {
                $response_array = json_decode($response->body(), true);
                if (array_key_exists("errors", $response_array)) {
                    Log::error("SENDGRID", "delete_all_blocks", $response_array["errors"]);
                } else {
                    Log::error("SENDGRID", "delete_all_blocks", $response_array);
                }
                $final = false;
            }
        } catch (Exception $e) {
            Log::error("SENDGRID", "delete_all_blocks", $e->getMessage());
        }

        return $final;
    }

    public function clear_junk_emails()
    {

        $time = time();

        $start_time = $time - (24 * 3600 * 1);

        $end_time = $time;

        $bounces = $this->get_bounces($start_time, $end_time);

        foreach ($bounces as $bounce) {

            $reason = $bounce["reason"];
            $criteria_to_delete = array(
                "RecipientNotFound",
                "User unknown",
                "Address rejected",
                "recipient is not exist",
                "Invalid recipient",
                "The email account that you tried to reach does not exist",
                "Recipient address rejected: Access denied",
                "RecipNotFound",
                "address is unknown",
                "mailbox unavailable",
                "Addressee unknown",
                "The email account that you tried to reach is disabled",
                "No Such User Here"
            );

            $delete = false;
            foreach ($criteria_to_delete as $criteria) {
                // print($reason." ".$criteria);
                if (stripos(strtolower($reason), strtolower($criteria)) !== false) {
                    $delete = true;
                }
            }

            $flag_wp_ = true;

            if (email_exists($bounce["email"])) {
                // print_r("NOT Deleting because it exists in WP: " . $bounce["email"] . "<br/>");
                if ($delete) {
                    // print_r("But address is unknown" . "<br/>");
                } else {
                    // print_r("REAL BOUNCE" . "<br/>");
                }
            } else if ($delete) {
                // print_r("Deleting email: " . $bounce["email"] . "<br/>");
                $this->delete_contact_by_email($bounce["email"]);
            } else {
                // print_r("Email stays with the following reason: " . $bounce["email"] . " " . $reason . ".<br/>");
            }
        }
    }


    public function clear_supressions_from_memebers_list()
    {
        try {
            $response = $this->client->_("asm/groups/" . $this->UNSUBSCRIBE_GROUP_MEMBERS . "/suppressions")->get();
            if (in_array($response->statusCode(), $this->CODE_SUCCESS)) {
                $response_array = json_decode($response->body(), true);
                // var_dump($response);
                foreach ($response_array as $email) {
                    $query_params = array(
                        "group_id" => $this->UNSUBSCRIBE_GROUP_MEMBERS,
                        "email" => $email
                    );
                    $extra_debug = "asm/groups/" . $this->UNSUBSCRIBE_GROUP_MEMBERS . "/suppressions/" . $email;
                    $response_2 = $this->client->_("asm/groups/" . $this->UNSUBSCRIBE_GROUP_MEMBERS . "/suppressions/" . $email)->delete($query_params);
                    // var_dump($response_2);
                    if (in_array($response_2->statusCode(), $this->CODE_SUCCESS)) {
                    } else {
                        $response_array_ = json_decode($response_2->body(), true);
                        if (array_key_exists("errors", $response_array_)) {
                            Log::error("SENDGRID", "clear_supressions_from_memebers_list", $response_array_["errors"]);
                            Log::error("SENDGRID", "clear_supressions_from_memebers_list", $response_2);
                            Log::error("SENDGRID", "clear_supressions_from_memebers_list", $extra_debug);
                        } else {
                            Log::error("SENDGRID", "clear_supressions_from_memebers_list", $response_2);
                            Log::error("SENDGRID", "clear_supressions_from_memebers_list", $extra_debug);
                            Log::error("SENDGRID", "clear_supressions_from_memebers_list", $response_array_);
                        }
                    }
                }
            } else {
                $response_array = json_decode($response->body(), true);
                if (array_key_exists("errors", $response_array)) {
                    Log::error("SENDGRID", "clear_supressions_from_memebers_list", $response_array["errors"]);
                } else {
                    Log::error("SENDGRID", "clear_supressions_from_memebers_list", $response_array);
                }
            }
            return false;
        } catch (Exception $e) {
            Log::error("SENDGRID", "clear_supressions_from_memebers_list", $e->getMessage());
        }
    }

    private function get_user_in_sengrid($identifier, $by_email_or_id = "email")
    {

        $output = array();

        if ($by_email_or_id == "email") {

            $query_params = array("query" => "email LIKE '" . $identifier . "%'");
            try {
                $response = $this->client->_("marketing/contacts/search")->post($query_params);
                // $this->local_debug($response, "Search for user " . $identifier);
                if (in_array($response->statusCode(), $this->CODE_SUCCESS)) {
                    $objects_array = json_decode($response->body(), true);
                    if (array_key_exists("result", $objects_array)) {
                        foreach ($objects_array["result"] as $object) {
                            // $this->local_debug($object, "Search User Loop");
                            if (!is_array($object)) {
                                $object = (array) $object;
                            }
                            if (array_key_exists("email", $object) and $object["email"] == $identifier) {
                                array_push($output, $object);
                                // $this->local_debug("Already have this user", "USER LOGIC");
                            } else {
                                // $this->local_debug("Do not have this user", "USER LOGIC");
                            }
                        }
                    }
                } else {
                    Log::error("SENDGRID", "get_user_in_sengrid", "Error on user getting user by identifier " . $identifier);
                    $response_array = json_decode($response->body(), true);
                    // $this->local_debug($response, "DEBUG THIS");
                    if (array_key_exists("errors", $response_array)) {
                        Log::error("SENDGRID", "get_user_in_sengrid", $response_array["errors"]);
                    } else {
                        Log::error("SENDGRID", "get_user_in_sengrid", $response_array);
                    }
                }
            } catch (Exception $e) {
                Log::error("SENDGRID", "get_user_in_sengrid", $e->getMessage());
            }
        } else {
            try {
                $response = $this->client->contactdb()->recipients()->_($identifier)->get();
                if (in_array($response->statusCode(), $this->CODE_SUCCESS)) {
                    $object = json_decode($response->body(), true);
                    if (!is_array($object)) {
                        $object = (array) $object;
                    }
                    if (array_key_exists("id", $object) and $object["id"] == $identifier) {
                        array_push($output, $object);
                    }
                } else {
                    Log::error("SENDGRID", "get_user_in_sengrid", "Error on user get_user_in_sengrid " . $identifier);
                    $response_array = json_decode($response->body(), true);
                    if (array_key_exists("errors", $response_array)) {
                        Log::error("SENDGRID", "get_user_in_sengrid", $response_array["errors"]);
                    } else {
                        Log::error("SENDGRID", "get_user_in_sengrid", $response_array);
                    }
                }
            } catch (Exception $e) {
                Log::error("SENDGRID", "get_user_in_sengrid", $e->getMessage());
            }
        }

        return $output;
    }

    private function separate_reserved_custom($user_to_be_added, $reserved, $custom)
    {

        $output = array();

        try {
            $this->local_debug(count($user_to_be_added), "Count number of users to be added");
            foreach ($user_to_be_added as $_object) {
                $new_user = array();
                $custom_fields = array();
                foreach ($_object as $prop => $value) {

                    if ($this->in_array_custom($prop, $reserved)) {
                        $new_user[$prop] = $value;
                    } else {
                        $custom_object = null;
                        foreach ($custom as $_object) {
                            foreach ($_object as $_prop => $_value) {
                                if ($_prop == "name" and $prop == $_value) {
                                    $custom_object = $_object;
                                }
                            }
                        }
                        if (array_key_exists("id", $custom_object)) {
                            // $this->local_debug($custom_object,"ID from Custom Property");
                            $custom_fields[$custom_object["id"]] = $value;
                            // $new_user[$custom_object["name"]] = $value;
                        }
                    }
                }

                if (count($custom_fields) > 0) {
                    $new_user["custom_fields"] = $custom_fields;
                }

                array_push($output, $new_user);
            }
        } catch (Exception $e) {
            Log::error("SENDGRID", "delete_contact_by_email", $e->getMessage());
        }

        return $output;
    }

    private function sub_add_user($user_to_be_added, $user_properties)
    {

        // $subset_users = array_slice($user_to_be_added,0,100);
        // $user_to_be_added = array_slice($user_to_be_added,0,100);
        try {
            // Log::error("SENDGRID", "sub_add_user user_list", json_encode($user_to_be_added));
            // Log::error("SENDGRID", "sub_add_user properties", json_encode($user_properties));
            if (is_array($user_to_be_added)) {

                if (count($user_to_be_added) == count($user_to_be_added, COUNT_RECURSIVE)) {
                    $user_to_be_added = array($user_to_be_added);
                }
            }

            $user_to_be_added = $this->separate_reserved_custom($user_to_be_added, $user_properties["reserved"], $user_properties["custom"]);
            $query_params = array("contacts" => $user_to_be_added);
            // Log::error("SENDGRID", "query_params", json_encode($query_params));


            // $this->local_debug($query_params, "BODY Add User");
            $this->local_debug("BODY Add User", "sub_add_user", json_encode($query_params));



            // print_r($query_params);
            $response = $this->client->_("marketing/contacts")->put($query_params);
            // print_r($response);
            // Log::error("SENDGRID", "marketing/contacts", $response);

            // exit(0);

            if (in_array($response->statusCode(), $this->CODE_SUCCESS)) {
                return true;
            } else {
                Log::error("SENDGRID", "sub_add_user", "Error on adding a user");

                $response_array = json_decode($response->body(), true);
                if (array_key_exists("errors", $response_array)) {
                    Log::error("SENDGRID", "sub_add_user", $response_array["errors"]);
                } else {
                    Log::error("SENDGRID", "sub_add_user", $response_array);
                }
            }
            // $this->local_debug($response, "response Add User");
        } catch (Exception $e) {
            Log::error("SENDGRID", "sub_add_user", $e->getMessage());
        }

        return false;
    }


    //wp_mail($email, $subject, $html, $this->email_header, $this->attachments);


    // private function update_user($user_to_be_added, $users_from_sendgrid_that_matched_this_id_email)
    // {

    //     if (is_array($user_to_be_added)) {

    //         if (count($user_to_be_added) == count($user_to_be_added, COUNT_RECURSIVE)) {
    //             $users_to_add = array($user_to_be_added);
    //         }

    //         $query_params = array("contacts"=>$user_to_be_added);

    //         try {
    //             $response = $this->client->_("/marketing/contacts")->put($query_params);
    //         } catch (Exception $e) {
    //             echo 'Caught exception: ',  $e->getMessage(), "\n";
    //             //LOGGER ERROR REPORT
    //         }
    //     }


    //     // if (count($recipients) == count($user_to_be_added, COUNT_RECURSIVE)) {
    //     //     $user_to_be_added = array($user_to_be_added);
    //     // }
    //     // foreach($users_from_sendgrid_that_matched_this_id_email as $sendgrid_user){
    //     //     if($this->comapare_users($user_to_be_added,$sendgrid_user)){
    //     //         //NOTHING TO UPDATE if they are the same
    //     //     } else {
    //     //         try {
    //     //             $response = $this->client->contactdb()->recipients()->patch($request_body);

    //     //         } catch (Exception $e) {
    //     //             echo 'Caught exception: ',  $e->getMessage(), "\n";
    //     //         }
    //     //     }
    //     // }
    // }

    private function comapare_users($user1, $user2)
    {
        $result = array_diff_assoc($user1, $user2);
        if (count($result) > 0) {
            return false;
        }

        return true;
    }


    public function update_user($recipients, $lists = array())
    {
        $this->add_user($recipients);
    }

    public function add_user($recipients, $lists = array())
    {

        $this->local_debug("Entering Add User");
        try {
            //arary or string



            $users_to_add = array();
            $failed_users = array();

            if (is_array($recipients)) {

                if ($this->LOCAL_DEBUG_FLAG) echo ("Users to add Rec ");

                if (count($recipients) == count($recipients, COUNT_RECURSIVE)) {
                    $users_to_add = array($recipients);
                } else {
                    $users_to_add = $recipients;
                }
            }

            $this->local_debug($users_to_add,"Users to add");

            $user_properties = $this->get_user_fields();

            $this->local_debug($user_properties,"Properties that exist");


            foreach ($users_to_add as $user_to_be_added) {
                $quality_control = $this->verify_user_to_sendgrid($user_to_be_added);
                if (!$quality_control) {
                    array_push($failed_users, $user_to_be_added);
                } else {
                    foreach ($user_to_be_added as $prop => $value) {
                        $this->local_debug($prop,"Check if Propertie exist");
                        if (!$this->in_array_custom($prop, $user_properties["reserved"]) and !$this->in_array_custom($prop, $user_properties["custom"])) {
                            $this->add_user_metadata($prop);
                            $this->local_debug($prop, "Addedd Property");
                        } else {
                            $this->local_debug($prop,"Already Exists");
                        }
                    }

                    // $users_in_sendgrid = $this->get_user_in_sengrid($user_to_be_added["email"], "email");
                    // if (count($users_in_sendgrid) == 0) {

                    // } else {
                    // $this->update_user($user_to_be_added, $users_in_sendgrid);
                    //}
                }
            }

            $this->sub_add_user($users_to_add, $user_properties);
        } catch (Exception $e) {
            Log::error("SENDGRID", "add_user", $e->getMessage());
        }
    }


    /*
    Required fields:

    first_name
    last_name
    email
    role
    paying ( yes or no )

    //Optional

    And you can add any other metadata you want.



    */


    private function compare_user($user_master, $user_to_compare_to)
    {

        try {
            // $this->local_debug($user_master,"MASTER");
            // $this->local_debug($user_to_compare_to,"COMPARE TO");

            $object_boolean = array();

            $flatten_sendgrid_user = array();

            $sendgrid_flat = array_merge($user_to_compare_to, $user_to_compare_to["custom_fields"]);


            // $this->local_debug($sendgrid_flat,"Sengrid Flat");

            $final_comparison = true;

            foreach ($user_master as $user_prop => $value) {
                foreach ($sendgrid_flat as $new_prop => $new_value) {
                    if ($user_prop == $new_prop) {
                        if ($value == $new_value) {
                            $object_boolean[$user_prop] = true;
                        } else {
                            $object_boolean[$user_prop] = false;
                            $final_comparison = false;
                        }
                    }
                }
            }

            // $this->local_debug($object_boolean,"Final Comparison");

            $user_master_keys = array_keys($user_master);
            $user_to_compare_keys = array_keys($sendgrid_flat);

            $containsAllValues = 0 == count(array_diff($user_master_keys, $user_to_compare_keys));


            // $this->local_debug($containsAllValues,"Contains All Values");

            //Check if all values match
            if (!$final_comparison) return false;

            if ($containsAllValues) return true;
        } catch (Exception $e) {
            Log::error("SENDGRID", "compare_user", $e->getMessage());
        }

        return false;
    }

    public function perform_exhaustive_integrity_check($array_from_wp_all_users)
    {
        try {

            $query_params["file_type"] = "csv";
            $response = $this->client->_("marketing/contacts/exports")->post($query_params);
            $response_array = json_decode($response->body(), true);
            // $this->local_debug($response, "exports");
            if (in_array($response->statusCode(), $this->CODE_SUCCESS)) {
                $id = $response_array["id"];
                $max_iterations = 60;
                $counter = 0;
                sleep(1);
                while ($counter < $max_iterations) {
                    $response = $this->client->_("marketing/contacts/exports/" . $id)->get();

                    $response_array = json_decode($response->body(), true);


                    if (in_array($response->statusCode(), $this->CODE_SUCCESS)) {
                        if ($response_array["status"] == "ready" and count($response_array["urls"]) > 0) {
                            $counter = 9999;
                            $url = $response_array["urls"][0];
                            $path = "settlement_file/test.csv";
                            // $this->local_debug($url, "URL");



                            $ch = curl_init($url);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            $ret = curl_exec($ch);
                            curl_close($ch);
                            $ret = gzdecode($ret);
                            $rows = explode("\n", $ret);
                            $EMAIL = 0;
                            $FIRST_NAME = 1;
                            $LAST_NAME = 2;
                            $ROLE = 18;


                            $user_matched_array = array();
                            $user_found_but_no_the_same = array();
                            $user_no_in_wp = array();

                            $skip_first_line = true;
                            foreach ($rows as $row) {
                                if ($skip_first_line) {
                                    $skip_first_line = false;
                                    continue;
                                }

                                $_row = str_getcsv($row);
                                $smaller_array = array("email" => $_row[$EMAIL], "first_name" => $_row[$FIRST_NAME], "last_name" => $_row[$LAST_NAME], "role" => $_row[$ROLE]);

                                $user_matched = false;

                                foreach ($array_from_wp_all_users as $wp_user) {



                                    if (!$user_matched) {
                                        if (strtolower($wp_user["email"]) == strtolower($smaller_array["email"])) {
                                            if (
                                                strtolower($wp_user["email"]) == strtolower($smaller_array["email"]) and
                                                strtolower($wp_user["first_name"]) == strtolower($smaller_array["first_name"]) and
                                                strtolower($wp_user["last_name"])  == strtolower($smaller_array["last_name"]) and
                                                strtolower($wp_user["role"]) == strtolower($smaller_array["role"])
                                            ) {

                                                array_push($user_matched_array, $smaller_array);
                                                $user_matched = true;
                                            } else {
                                                array_push($user_found_but_no_the_same, $wp_user, $_row);
                                                $user_matched = true;
                                            }
                                        }
                                    }
                                }

                                if ($user_matched == false and strtolower($smaller_array["role"]) != "" and strtolower($smaller_array["role"] != "fundstrat")) {
                                    array_push($user_no_in_wp, $smaller_array);
                                }
                            }
                            // $this->local_debug($user_matched_array, "user_matched");
                            // $this->local_debug($user_found_but_no_the_same, "user_found_but_no_the_same");
                            // $this->local_debug($user_no_in_wp, "user_no_in_wp");


                            foreach ($user_found_but_no_the_same as $to_check) {

                                // print_r($to_check);

                            }

                            $this->report_to_slack($user_no_in_wp);
                            foreach ($user_no_in_wp as $to_delete) {
                                $this->local_debug($to_delete, "DELETING....");
                                $this->delete_contact_by_email($to_delete["email"]);
                            }
                        } else {
                            // $this->local_debug($counter, "Waiting");
                            $counter = $counter + 1;
                            sleep(1);
                        }
                    } else {
                        Log::error("SENDGRID", "integrity", $response_array);
                    }
                }
            } else {
            }
        } catch (Exception $e) {
            Log::error("SENDGRID", "integrity", $e->getMessage());
        }
    }

    public function report_to_slack($user_no_in_wp)
    {
        if (count($user_no_in_wp) > 0) {
            $reporter = new Notification();
            $reporter->report_to_slack("*Users NOT in WP that are subscribed to SENDGRID ---> DELETING *");
            // print("<pre>");
            foreach ($user_no_in_wp as $email) {
                // print_r($email);
                $reporter->report_to_slack($email["email"] . " " . $email["first_name"] . " " . $email["last_name"] . " " . $email["role"]);
            }
            // print("</pre>");
        }
    }


    public function integrity_sendgrid($array_from_wp_all_users)
    {

        try {
            $users_to_be_delete = array();
            $users_need_update = array();
            $good_users = array();

            $emails_wp = array();
            $emails_sendgrid = array();

            $this->add_user($array_from_wp_all_users);


            //REMOVE 1 way sync
            // $sendgrid_users_count =  $this->get_sendgrid_users_count();


            // $this->local_depusebug($sendgrid_users_count, "COUNT SENDGRID");

            // if (count($array_from_wp_all_users) < $sendgrid_users_count) {
            //     // $this->local_debug($sendgrid_users_count, "Sengrid Users Count");
            $this->perform_exhaustive_integrity_check($array_from_wp_all_users);
            // }
        } catch (Exception $e) {
            Log::error("SENDGRID", "integrity_sendgrid", $e->getMessage());
        }
    }

    private function get_sendgrid_users_count()
    {

        $output_contacts = array();

        try {
            $response = $this->client->_("marketing/contacts")->get();

            $response_array = json_decode($response->body(), true);

            if (in_array($response->statusCode(), $this->CODE_SUCCESS)) {

                if (array_key_exists("contact_count", $response_array)) {

                    return $response_array["contact_count"];
                }
            } else {
                Log::error("SENDGRID", "get_sendgrid_users", "Error on getting user");
                $response_array = json_decode($response->body(), true);
                if (array_key_exists("errors", $response_array)) {
                    Log::error("SENDGRID", "get_sendgrid_users", $response_array["errors"]);
                } else {
                    Log::error("SENDGRID", "get_sendgrid_users", $response_array);
                }
            }

            return $output_contacts;
        } catch (Exception $e) {
            Log::error("SENDGRID", "get_sendgrid_users", $e->getMessage());
        }
        return 0;
    }

    public function sync_supressions_from_sendgrid_to_wp_to_sendgrid()
    {

        // print_r("<pre>");
        $categories = array();
        $unsubs = array();


        //DIRECTION FROM SENDGRID TO WP
        try {
            $response = $this->client->_("asm/groups")->get();

            $response_array = json_decode($response->body(), true);

            if (in_array($response->statusCode(), $this->CODE_SUCCESS)) {


                foreach ($response_array as $object) {
                    $categories[$object["name"]] = $object["id"];
                    $response_2 = $this->client->_("asm/groups/" . $object["id"] . "/suppressions")->get();

                    $unsubs[trim($object['name'])] = array();

                    $response_array_2 = json_decode($response_2->body(), true);

                    if (in_array($response_2->statusCode(), $this->CODE_SUCCESS)) {



                        foreach ($response_array_2 as $email_to_update) {


                            array_push($unsubs[trim($object['name'])], $email_to_update);

                            $user = get_user_by('email', $email_to_update);
                            if (!$user) {
                                continue;
                            }

                            $_user = new User($user);

                            // if($email_to_update == "mamoorefield@gmail.com"){
                            //     print_r("This email has trouble");
                            // }

                            $category_name = trim($object['name']);
                            $category_id = get_cat_ID($category_name);

                            if($category_id == ""){

                                if(in_array($category_name,MANUAL_EMAIL_UNSUBSCRIBE_LISTS)){
                                    $slug = strtolower(str_replace(' ', '-', $category_name));
                                    $already_unsubscribed_from = $_user->get_manual_category_unbsubscribes();
                                    array_push($already_unsubscribed_from, $slug);
                                    $_user->set_manual_category_unbsubscribes($already_unsubscribed_from);
                                }

                            } else {

                                $category_ids = array();


                                $already_unsubscribed_from = $_user->get_category_unbsubscribes();
                                // if($email_to_update == "diego.czul@fsinsight.com"){
                                //     print_r(" | already_unsubscribed_from ");
                                //     print_r($already_unsubscribed_from);

                                //     print_r("Category ".$category_name);
                                //     print_r($category_id);
                                //     print_r($category_id);

                                    array_push($already_unsubscribed_from, $category_id);
                                // }
                                $all_unsubscribes = array_filter(array_unique($already_unsubscribed_from));

                                // if($email_to_update == "diego.czul@fsinsight.com"){
                                //     print_r("First Loop Unsubscribe");
                                //     print_r(" | email_to_update ");
                                //     print_r($email_to_update);
                                //     print_r(" | all_unsubscribes ");
                                //     print_r($all_unsubscribes);
                                //     print_r(" | ");
                                // }

                                if($email_to_update == "mamoorefield@gmail.com"){
                                    print_r($all_unsubscribes);
                                }

                                $_user->set_category_unbsubscribes($all_unsubscribes);

                                //WORDPRESS SET META
                                // CATEGORY NAME = $object["name"]
                                // SUPRRESS = TRUE == TOGGLE IS OFF
                                // EMAIL: $email_to_update
                            }

                        }
                    }
                }
            }
        } catch (Exception $e) {
            Log::error("SENDGRID", "get_suppression_group_id", $e->getMessage());
        }

        //DIRECTION FROM WP TO SENDGRID

        $list_of_global_unsubscribes = array();

        try {
            $response = $this->client->_("suppression/unsubscribes")->get();

            $response_array = json_decode($response->body(), true);

            if (in_array($response->statusCode(), $this->CODE_SUCCESS)) {


                foreach ($response_array as $elem) {
                    array_push($list_of_global_unsubscribes, $elem["email"]);
                }
            }
        } catch (Exception $e) {
            Log::error("SENDGRID", "get_suppression_group_id", $e->getMessage());
        }



        try {

            $args = array();

            $users = get_users($args);
            foreach ($users as $user) {

                $_user = new User($user);

                if ($_user->get_email() == "diego.czul@fsinsight.com") {
                    $already_unsubscribed_from = $_user->get_category_unbsubscribes();

                    // print_r("Array CAT 1 already_unsubscribed_from ");
                    // print_r($already_unsubscribed_from);

                    if (in_array($_user->get_email(), $list_of_global_unsubscribes)) {

                        // print_r(" Diego add  ");
                        $_user->set_meta('fs_category_unsubscribes_global', true);
                    } else {
                        $_user->set_meta('fs_category_unsubscribes_global', false);
                        // print_r(" Diego remove");
                    }

                    $new_output = array();
                    foreach ($already_unsubscribed_from as $cat_to_alter) {
                        $category = get_category($cat_to_alter);
                        $category_name = trim($category->cat_name);
                        if (in_array($_user->get_email(), $unsubs[$category_name])) {
                            // print_r("Adding this user to supression group: ".$category_name." ".$_user->get_email()."</br>");
                            // $this->add_user_to_supression_group($_user->get_email(), $category_name, false);
                            array_push($new_output, $category->term_id);
                        }
                    }
                    // print_r("Array CAT");
                    // print_r($new_output);
                    if(is_array($new_output)){
                        $_user->set_category_unbsubscribes($new_output);
                    }
                }
            }
        } catch (Exception $e) {
            Log::error("SENDGRID", "get_suppression_group_id", $e->getMessage());
        }





        // print_r("</pre>");
    }

    public function reset_supressions_from_sendgrid_to_wp_to_sendgrid()
    {

        // print_r("<pre>");
        $categories = array();
        $unsubs = array();

        // print_r("starting suppresion");

        //DIRECTION FROM SENDGRID TO WP
        try {
            $response = $this->client->_("asm/groups")->get();

            $response_array = json_decode($response->body(), true);

            if (in_array($response->statusCode(), $this->CODE_SUCCESS)) {


                foreach ($response_array as $object) {
                    $categories[$object["name"]] = $object["id"];
                    $response_2 = $this->client->_("asm/groups/" . $object["id"] . "/suppressions")->get();

                    // print_r($object["id"]);
                    // print_r("<br/>");

                    $unsubs[trim($object['name'])] = array();

                    $response_array_2 = json_decode($response_2->body(), true);

                    if (in_array($response_2->statusCode(), $this->CODE_SUCCESS)) {



                        foreach ($response_array_2 as $email_to_update) {

                            // if ($email_to_update == "diego.czul@fsinsight.com") {
                                // print_r("asm/groups/" . $object["id"] . "/suppressions/" . $email_to_update);
                                $response = $this->client->_("asm/groups/" . $object["id"] . "/suppressions/" . $email_to_update)->delete();
                            // }
                        }
                    }
                }
                $this->sync_supressions_from_sendgrid_to_wp_to_sendgrid();
            }
        } catch (Exception $e) {
            Log::error("SENDGRID", "get_suppression_group_id", $e->getMessage());
        }


        // print_r("</pre>");
    }



    public function get_suppression_group_id($supression_name)
    {

        $categories = array();

        try {
            $response = $this->client->_("asm/groups")->get();

            $response_array = json_decode($response->body(), true);

            if (in_array($response->statusCode(), $this->CODE_SUCCESS)) {


                foreach ($response_array as $object) {
                    $categories[$object["name"]] = $object["id"];
                }


                if (array_key_exists($supression_name, $categories)) {
                    return $categories[$supression_name];
                }
            }
        } catch (Exception $e) {
            Log::error("SENDGRID", "get_suppression_group_id", $e->getMessage());
            return 0;
        }

        return 0;
    }


    public function add_user_to_supression_group($email, $supression_name, $enabled = true)
    {
        try {

            $categories = array();

            $response = $this->client->_("asm/groups")->get();

            $response_array = json_decode($response->body(), true);

            if (in_array($response->statusCode(), $this->CODE_SUCCESS)) {


                foreach ($response_array as $object) {
                    $categories[$object["name"]] = $object["id"];
                }


                if (array_key_exists($supression_name, $categories)) {

                    if ($enabled) {

                        try {
                            $response = $this->client->_("asm/groups/" . $categories[$supression_name] . "/suppressions/" . $email)->delete();
                            // print_r($response);
                            if (in_array($response->statusCode(), $this->CODE_SUCCESS)) {
                                return true;
                            } else {
                                Log::error("SENDGRID", "add_user_to_supression_group", "Error on user metadata response");
                                Log::error("SENDGRID", "add_user_to_supression_group", json_encode($response));
                                $response_array = json_decode($response->body(), true);
                                if (array_key_exists("errors", $response_array)) {
                                    Log::error("SENDGRID", "add_user_to_supression_group", $response_array["errors"]);
                                } else {
                                    Log::error("SENDGRID", "add_user_to_supression_group", $response_array);
                                }
                                return false;
                            }
                        } catch (Exception $e) {
                            Log::error("SENDGRID", "add_user_to_supression_group", $e->getMessage());
                        }
                    } else {

                        $request_body = array();
                        $request_body["recipient_emails"] = array($email);

                        try {

                            // print_r($request_body);


                            $response = $this->client->_("asm/groups/" . $categories[$supression_name] . "/suppressions")->post($request_body);

                            // print_r($response);



                            if (in_array($response->statusCode(), $this->CODE_SUCCESS)) {
                                return true;
                            } else {
                                Log::error("SENDGRID", "add_user_to_supression_group", "Error on user metadata response");
                                Log::error("SENDGRID", "add_user_to_supression_group", json_encode($response));
                                $response_array = json_decode($response->body(), true);
                                if (array_key_exists("errors", $response_array)) {
                                    Log::error("SENDGRID", "add_user_to_supression_group", $response_array["errors"]);
                                } else {
                                    Log::error("SENDGRID", "add_user_to_supression_group", $response_array);
                                }
                                return false;
                            }
                        } catch (Exception $e) {
                            Log::error("SENDGRID", "add_user_to_supression_group", $e->getMessage());
                        }
                    }
                } else {
                    $this->get_wp_categories_verify_supression_groups();
                    Log::error("SENDGRID", "add_user_to_supression_group", $response_array["errors"]);
                }
            } else {
                Log::error("SENDGRID", "add_user_to_supression_group", "Error on getting supressions");
                $response_array = json_decode($response->body(), true);
                if (array_key_exists("errors", $response_array)) {
                    Log::error("SENDGRID", "add_user_to_supression_group", $response_array["errors"]);
                } else {
                    Log::error("SENDGRID", "add_user_to_supression_group", $response_array);
                }
            }
        } catch (Exception $e) {
            Log::error("SENDGRID", "add_user_to_supression_group", $e->getMessage());
        }
    }

    public function add_user_to_supression_group_by_id($email, $supression_id, $enabled = true)
    {
        try {

            $categories = array();

            if ($enabled) {

                try {
                    $response = $this->client->_("asm/groups/" . $supression_id . "/suppressions/" . $email)->delete();

                    if (in_array($response->statusCode(), $this->CODE_SUCCESS)) {
                        return true;
                    } else {
                        Log::error("SENDGRID", "add_user_to_supression_group", "Error on user metadata response");
                        Log::error("SENDGRID", "add_user_to_supression_group", json_encode($response));
                        $response_array = json_decode($response->body(), true);
                        if (array_key_exists("errors", $response_array)) {
                            Log::error("SENDGRID", "add_user_to_supression_group", $response_array["errors"]);
                        } else {
                            Log::error("SENDGRID", "add_user_to_supression_group", $response_array);
                        }
                        return false;
                    }
                } catch (Exception $e) {
                    Log::error("SENDGRID", "add_user_to_supression_group", $e->getMessage());
                }
            } else {

                $request_body = array();
                $request_body["recipient_emails"] = array($email);

                try {

                    // print_r($request_body);


                    $response = $this->client->_("asm/groups/" . $supression_id . "/suppressions")->post($request_body);

                    // print_r($response);



                    if (in_array($response->statusCode(), $this->CODE_SUCCESS)) {
                        return true;
                    } else {
                        Log::error("SENDGRID", "add_user_to_supression_group", "Error on user metadata response");
                        Log::error("SENDGRID", "add_user_to_supression_group", json_encode($response));
                        $response_array = json_decode($response->body(), true);
                        if (array_key_exists("errors", $response_array)) {
                            Log::error("SENDGRID", "add_user_to_supression_group", $response_array["errors"]);
                        } else {
                            Log::error("SENDGRID", "add_user_to_supression_group", $response_array);
                        }
                        return false;
                    }
                } catch (Exception $e) {
                    Log::error("SENDGRID", "add_user_to_supression_group", $e->getMessage());
                }
            }
        } catch (Exception $e) {
            Log::error("SENDGRID", "add_user_to_supression_group", $e->getMessage());
        }
    }

    public function add_remove_global_supression_group($email, $add_remove = "add")
    {
        try {

            $categories = array();


            if ($add_remove == "remove") {

                try {
                    $response = $this->client->_("asm/suppressions/global/" . $email)->delete();

                    //print_r("asm/supressions/global/".$email);

                    if (in_array($response->statusCode(), $this->CODE_SUCCESS)) {
                        return true;
                    } else {
                        Log::error("SENDGRID", "add_remove_global_supression_group", "Error on user metadata response");
                        Log::error("SENDGRID", "add_remove_global_supression_group", json_encode($response));
                        $response_array = json_decode($response->body(), true);

                        if (array_key_exists("errors", $response_array)) {
                            Log::error("SENDGRID", "add_remove_global_supression_group", $response_array["errors"]);
                        } else {
                            Log::error("SENDGRID", "add_remove_global_supression_group", $response_array);
                        }
                        return false;
                    }
                } catch (Exception $e) {
                    Log::error("SENDGRID", "add_remove_global_supression_group", $e->getMessage());
                }
            } else {

                $request_body = array();
                $request_body["recipient_emails"] = array($email);

                try {

                    //print_r($request_body);


                    $response = $this->client->_("asm/suppressions/global")->post($request_body);

                    //print_r($response);



                    if (in_array($response->statusCode(), $this->CODE_SUCCESS)) {
                        return true;
                    } else {
                        Log::error("SENDGRID", "add_remove_global_supression_group", "Error on user metadata response");
                        Log::error("SENDGRID", "add_remove_global_supression_group", json_encode($response));
                        $response_array = json_decode($response->body(), true);
                        //print_r($response_array);
                        if (array_key_exists("errors", $response_array)) {
                            Log::error("SENDGRID", "add_remove_global_supression_group", $response_array["errors"]);
                        } else {
                            Log::error("SENDGRID", "add_remove_global_supression_group", $response_array);
                        }
                        return false;
                    }
                } catch (Exception $e) {
                    Log::error("SENDGRID", "add_remove_global_supression_group", $e->getMessage());
                }
            }
        } catch (Exception $e) {
            Log::error("SENDGRID", "add_remove_global_supression_group", $e->getMessage());
        }
    }

    public function get_wp_categories_verify_supression_groups()
    {

        $hardcoded_categories_that_should_exist = array();
        $categories = array();
        $wp_categories = array();
        $wp_categories_source = get_categories(array("depth" => 0));


        foreach ($wp_categories_source as $cat) {
            array_push($wp_categories, $cat->name);
        }

        // print_r($categories);


        try {
            $response = $this->client->_("asm/groups")->get();

            $response_array = json_decode($response->body(), true);

            if (in_array($response->statusCode(), $this->CODE_SUCCESS)) {


                foreach ($response_array as $object) {
                    array_push($categories, $object["name"]);
                }

                $result = array_diff($wp_categories, $categories);

                // print_r($result);

                foreach ($result as $to_add) {
                    $request_body = array();
                    $request_body["name"] = $to_add;
                    $request_body["description"] = $to_add;

                    // print_r($request_body);

                    try {
                        // $this->local_debug($request_body, "Add User Metadata POST");
                        $response = $this->client->_("asm/groups")->post($request_body);
                        // $this->local_debug($response, "Add User Metadata");
                        if (in_array($response->statusCode(), $this->CODE_SUCCESS)) {
                            // return true;
                        } else {
                            Log::error("SENDGRID", "add_supression", "Error on user metadata response");
                            Log::error("SENDGRID", "add_supression", json_encode($response));
                            $response_array = json_decode($response->body(), true);
                            if (array_key_exists("errors", $response_array)) {
                                Log::error("SENDGRID", "add_supression", $response_array["errors"]);
                            } else {
                                Log::error("SENDGRID", "add_supression", $response_array);
                            }
                            // return false;
                        }
                    } catch (Exception $e) {
                        Log::error("SENDGRID", "add_supression", $e->getMessage());
                    }
                }
            } else {
                Log::error("SENDGRID", "get_wp_categories_verify_supression_groups", "Error on getting supressions");
                $response_array = json_decode($response->body(), true);
                if (array_key_exists("errors", $response_array)) {
                    Log::error("SENDGRID", "get_wp_categories_verify_supression_groups", $response_array["errors"]);
                } else {
                    Log::error("SENDGRID", "get_wp_categories_verify_supression_groups", $response_array);
                }
            }
        } catch (Exception $e) {
            Log::error("SENDGRID", "get_wp_categories_verify_supression_groups", $e->getMessage());
        }
    }

    private function in_array_custom($prop, $objects)
    {


        foreach ($objects as $object) {
            foreach ($object as $prop_key => $value) {
                if ($prop_key == "name" and $value == $prop) {

                    return true;
                }
            }
        }

        return false;
    }

    public function remove_user()
    {
    }



    public function event()
    {
        if ($this->events == null) {
            $this->events = new SendGridEvents();
        }

        return $this->events;
    }


    /*

    name: Post Name
    catgory: Product Category
    send_at: when to sent the post $objDateTime = new DateTime('NOW'); echo $objDateTime->format(DateTime::ISO8601);

    */

    public function get_segment($segment)
    {

        try {
            $response = $this->client->_("marketing/segments")->get();

            $response_array = json_decode($response->body(), true);


            if (in_array($response->statusCode(), $this->CODE_SUCCESS)) {
                // $this->local_debug($response, "Segments Available");

                $response_array = json_decode($response->body(), true);

                foreach ($response_array["results"] as $object) {
                    if ($object["name"] == $segment) {
                        return $object;
                    }
                }
            } else {

                Log::error("SENDGRID", "get_segment", "Error on getting user");
                if (array_key_exists("errors", $response_array)) {
                    Log::error("SENDGRID", "get_segment", $response_array["errors"]);
                } else {
                    Log::error("SENDGRID", "get_segment", $response_array);
                }
            }
        } catch (Exception $e) {
            Log::error("SENDGRID", "get_segment", $e->getMessage());
        }
        return null;
    }

    public function get_bounces($start_time, $end_time = null)
    {

        if (!$end_time) {
            $end_time = time();
        }

        $response_array = array();

        try {
            $response = $this->client->_("suppression/bounces?start_time=" . $start_time . "&end_time=" . $end_time)->get();


            $response_array = json_decode($response->body(), true);


            if (in_array($response->statusCode(), $this->CODE_SUCCESS)) {
                // $this->local_debug($response, "Segments Available");
                $response_array = json_decode($response->body(), true);
            } else {
                Log::error("SENDGRID", "get_bounces", "Error on getting bounces");

                if (array_key_exists("errors", $response_array)) {
                    Log::error("SENDGRID", "get_bounces", $response_array["errors"]);
                } else {
                    Log::error("SENDGRID", "get_bounces", $response_array);
                }
            }
        } catch (Exception $e) {
            Log::error("SENDGRID", "get_bounces", $e->getMessage());
        }
        return $response_array;
    }



    public function get_email_that_bounced_by_post_subject($subject)
    {

        $limit = 999;
        $SPACE = "%20";
        $QUOTE = "%22";
        $EQUAL = "%3D";
        //MAX IS 1000

        //subject%3D%22L . Thomas Block FSI BLAST: Tom Block’s Takeaways: Biden appears to win, no blue wave, strong Republican showing in House and Senate, Biden agenda starts to emerge.%22%20AND%20status!=%22delivered%22
        //limit=999&query=subject%3D%22New FSInsight Report – FSInsight 3Q20 Daily Earnings Update – 11/12/2020%22%20AND%20status!=%22delivered%22
        //limit=999&query=subject%3D%22New FSInsight Report – FSInsight 3Q20 Daily Earnings Update – 11/12/2020%22%20AND%20status!=%22delivered%22


        $response_array = array();

        try {
            // $query = "?limit=". $limit . "&query=subject" . $EQUAL . $QUOTE . $subject . $QUOTE . $SPACE . "AND" . $SPACE . "status!=" . $QUOTE . "delivered" . $QUOTE;
            $query = "?limit=" . $limit . "&query=status!=" . $QUOTE . "delivered" . $QUOTE . $SPACE . "AND" . $SPACE . "subject=" . $QUOTE . urlencode($subject) . $QUOTE;
            // $query = "?limit=". $limit . '&query=subject="'.$subject . '" AND ' . 'status!="' . $QUOTE . "delivered" . '"';

            $response = $this->client->_("messages" . $query)->get();

            $response_array = json_decode($response->body(), true);

            if (in_array($response->statusCode(), $this->CODE_SUCCESS)) {
                // $this->local_debug($response, "Segments Available");
                $response_array = json_decode($response->body(), true);

                $emails = array();
                if (array_key_exists("messages", $response_array)) {
                    foreach ($response_array["messages"] as $message) {

                        array_push($emails, $message["to_email"]);
                    }
                }
                return $emails;
            } else {
                Log::error("SENDGRID", "get_email_that_bounced_by_post_subject", "Error on getting email bounces");

                if (array_key_exists("errors", $response_array)) {
                    Log::error("SENDGRID", "get_email_that_bounced_by_post_subject", $response_array["errors"]);
                } else {
                    Log::error("SENDGRID", "get_email_that_bounced_by_post_subject", $response_array);
                }
            }
        } catch (Exception $e) {
            Log::error("SENDGRID", "get_email_that_bounced_by_post_subject", $e->getMessage());
        }
        return $response_array;
    }

    public function get_spam_report($start_time, $end_time = null)
    {

        if (!$end_time) {
            $end_time = time();
        }

        $response_array = array();

        try {
            $response = $this->client->_("suppression/spam_reports")->get();

            $response_array = json_decode($response->body(), true);

            if (in_array($response->statusCode(), $this->CODE_SUCCESS)) {
                // $this->local_debug($response, "Segments Available");

            } else {
                Log::error("SENDGRID", "get_spam_report", "Error on getting spam reports");
                if (array_key_exists("errors", $response_array)) {
                    Log::error("SENDGRID", "get_spam_report", $response_array["errors"]);
                } else {
                    Log::error("SENDGRID", "get_spam_report", $response_array);
                }
            }
        } catch (Exception $e) {
            Log::error("SENDGRID", "get_spam_report", $e->getMessage());
        }
        return $response_array;
    }

    public function get_unsubscribes($start_time, $end_time = null)
    {

        if (!$end_time) {
            $end_time = time();
        }

        $response_array = array();

        try {
            $response = $this->client->_("suppression/unsubscribes")->get();

            $response_array = json_decode($response->body(), true);

            if (in_array($response->statusCode(), $this->CODE_SUCCESS)) {
                // $this->local_debug($response, "Segments Available");

            } else {
                Log::error("SENDGRID", "get_unsubscribes", "Error on getting spam reports");
                if (array_key_exists("errors", $response_array)) {
                    Log::error("SENDGRID", "get_unsubscribes", $response_array["errors"]);
                } else {
                    Log::error("SENDGRID", "get_unsubscribes", $response_array);
                }
            }
        } catch (Exception $e) {
            Log::error("SENDGRID", "get_unsubscribes", $e->getMessage());
        }
        return $response_array;
    }

    public function get_design($id)
    {

        $response_array = null;

        try {
            $response = $this->client->_("designs/" . $id)->get();

            $response_array = json_decode($response->body(), true);


            if (in_array($response->statusCode(), $this->CODE_SUCCESS)) {
                // $this->local_debug($response, "Segments Available");
                $response_array = json_decode($response->body(), true);
            } else {
                Log::error("SENDGRID", "get_design", "Error on getting spam reports");

                if (array_key_exists("errors", $response_array)) {
                    Log::error("SENDGRID", "get_design", $response_array["errors"]);
                } else {
                    Log::error("SENDGRID", "get_design", $response_array);
                }
            }
        } catch (Exception $e) {
            Log::error("SENDGRID", "get_design", $e->getMessage());
        }
        return $response_array;
    }

    public function get_design_by_name($name)
    {

        $objects = null;

        try {
            $response = $this->client->_("designs")->get();

            $response_array = json_decode($response->body(), true);

            if (in_array($response->statusCode(), $this->CODE_SUCCESS)) {

                // $this->local_debug($response, "Segments Available");
                foreach ($response_array["result"] as $object) {
                    // $this->local_debug($object, "Objects");
                    if ($object["name"] == $name) {

                        return $object;
                    }
                }

                return null;
            } else {
                Log::error("SENDGRID", "get_design_by_name", "Error on getting spam reports");

                if (array_key_exists("errors", $response_array)) {
                    Log::error("SENDGRID", "get_design_by_name", $response_array["errors"]);
                } else {
                    Log::error("SENDGRID", "get_design_by_name", $response_array);
                }
            }
        } catch (Exception $e) {
            Log::error("SENDGRID", "get_design_by_name", $e->getMessage());
        }
        return null;
    }

    public function get_todays_campaings_sent($limit = 10)
    {

        //TODO
        $objects = null;

        try {
            $response = $this->client->_("marketing/singlesends?page_size=" . $limit)->get();

            $response_array = json_decode($response->body(), true);

            if (in_array($response->statusCode(), $this->CODE_SUCCESS)) {
                // $this->local_debug($response, "Segments Available");

                return $response_array["result"];
            } else {
                Log::error("SENDGRID", "get_unsubscribes", "Error on getting spam reports");
                if (array_key_exists("errors", $response_array)) {
                    Log::error("SENDGRID", "get_unsubscribes", $response_array["errors"]);
                } else {
                    Log::error("SENDGRID", "get_unsubscribes", $response_array);
                }
            }
        } catch (Exception $e) {
            Log::error("SENDGRID", "get_unsubscribes", $e->getMessage());
        }
        return null;
    }


    public function get_stats($start_time, $end_time = null)
    {
        //TODO
        $objects = null;

        try {
            $response = $this->client->_("designs")->get();

            $response_array = json_decode($response->body(), true);

            if (in_array($response->statusCode(), $this->CODE_SUCCESS)) {
                // $this->local_debug($response, "Segments Available");

                return $response_array;
            } else {
                Log::error("SENDGRID", "get_unsubscribes", "Error on getting spam reports");
                if (array_key_exists("errors", $response_array)) {
                    Log::error("SENDGRID", "get_unsubscribes", $response_array["errors"]);
                } else {
                    Log::error("SENDGRID", "get_unsubscribes", $response_array);
                }
            }
        } catch (Exception $e) {
            Log::error("SENDGRID", "get_unsubscribes", $e->getMessage());
        }
        return null;
    }
}
