<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessFeaturedImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $headline;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($headline)
    {
        //
        $this->headline = $headline;
    }

    /**
     * Execute the job.
     *
     * @return void
     */

    function get_web_page($url)
    {




        $res = array();
        $proxies = config("constants.proxies_list");
        $proxy = $proxies[array_rand(config("constants.proxies_list"))];
        $options = array(
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // do not return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_PROXY => $proxy,
            CURLOPT_USERAGENT      => "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.52 Safari/537.17", // who am i
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_TIMEOUT        => 30,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
        );
        $ch      = curl_init($url);
        curl_setopt_array($ch, $options);
        $content = curl_exec($ch);
        if (preg_match('~Location: (.*)~i', $content, $match)) {
            $location = trim($match[1]);
        }
        $err     = curl_errno($ch);
        $errmsg  = curl_error($ch);
        $header  = curl_getinfo($ch);
        curl_close($ch);

        $res['content'] = $content;
        $res['url'] = $header['url'];

        $components = parse_url($res["url"]);
        if (array_key_exists("host", $components)) {
            if (strpos($components["host"], 'google') !== false) {
                parse_str($components['query'], $params);
                if (array_key_exists("url", $params)) {
                    $res['url'] = $params["url"];
                    $res['content'] = "";
                }
            }
        }

        return $res;
    }

    public function check_if_extension($string)
    {

        $exts = array('jpg', 'gif', 'png');

        if (
            filter_var($string, FILTER_VALIDATE_URL) &&
            in_array(strtolower(pathinfo($string, PATHINFO_EXTENSION)), $exts)
        ) {
            echo "It is an Image URL";
            return true;
        }
        return false;
    }

    public function handle()
    {
        //
        $size_array = array();
        $final = "";
        $html = "";

        if ($this->headline) {

            try {


                $url = $this->headline->link;

                $url = $this->get_web_page($url);

                print_r("\nAFTER: " . $url["url"] . "\n");

                if ($url["content"] == "" and $url["url"] != "") {
                    $html = file_get_contents($url["url"]);
                } else {
                    $html = $url["content"];
                }


                $doc = new \DOMDocument();
                @$doc->loadHTML($html);

                $tags = $doc->getElementsByTagName('img');

                foreach ($tags as $tag) {
                    $url = $tag->getAttribute('src');
                    $components = parse_url($url);
                    $url = strtok($url, '?');
                    if (strpos($url, 'data:image') !== false) {
                        continue;
                    }

                    if (is_array($components) and array_key_exists("query", $components)) {
                        parse_str($components['query'], $params);
                        if (array_key_exists("url", $params)) {
                            if ($this->check_if_extension($params["url"])) {
                                $final = $params["url"];
                                break;
                            }
                        }
                    }

                    if ($url) {
                        try {
                            $result = getimagesize($url);
                            if ($result[0] > 400) {
                                $final = $url;
                                break;
                            } else {
                                $size_array[$result[0]] = $url;
                            }
                        } catch (\Exception $e) {
                        }

                        // print_r($result);

                    }
                    //
                    // print_r($size_array);

                    //assign size as key and path as value to the newly created array
                }
                if ($final != "") {
                    // print("FINAL ".$final);
                } else {
                    $finder = new \DomXPath($doc);
                    $classname = "lazy-image";
                    $nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");
                    foreach ($nodes as $p) {
                        try {
                            if ($p->hasAttributes()) {
                                foreach ($p->attributes as $attr) {
                                    $name = $attr->nodeName;
                                    $value = $attr->nodeValue;
                                    if ($name == 'data-src') {
                                        $result = getimagesize($value);
                                        if ($result[0] > 400) {
                                            $final = $url;
                                            break;
                                        } else {
                                            $size_array[$result[0]] = $value;
                                        }
                                    }
                                }
                            }
                        } catch (\Exception $e) {
                        }
                    }
                    if (count($size_array) > 0) {
                        $max_size = max(array_keys($size_array)); // get max size from keys array
                        $max_file = $size_array[$max_size]; // find out file path based on max-size
                        $final = $max_file;
                    }
                }

                if ($final != "") {
                    $this->headline->image_path = $final;
                    $this->headline->save();
                    print_r("\nImage Saved " . $this->headline->id . "\n");
                } else {
                    print_r("\n**Image Not Saved " . $this->headline->id . "\n");
                }
            } catch (\Exception $e) {
                logger()->error($e);
            }
        }
    }
}
