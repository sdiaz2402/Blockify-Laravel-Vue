<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportLogos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Import:Logo';

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

    function CheckImageExists($imgUrl)
    {
        if (@GetImageSize($imgUrl)) {
            return true;
        } else {
            return false;
        };
    }

    function getFavicon($url, $dom)
    {

        try{
        $fallback_favicon = "";
        // $fallback_favicon = "http://stackoverflow.com/favicon.ico";


        // $dom = new \DOMDocument();
        // @$dom->loadHTML($url);
        $links = $dom->getElementsByTagName('link');
        $l = $links->length;
        $favicon = "/favicon.ico";
        for ($i = 0; $i < $l; $i++) {
            $item = $links->item($i);
            if (strcasecmp($item->getAttribute("rel"), "shortcut icon") === 0) {
                $favicon = $item->getAttribute("href");
                break;
            }
        }
        // print_r("URL ".$url."\n");
        $u = parse_url($url);
        // print_r($u);

        $subs = explode('.', $u['host']);
        $domain = $subs[count($subs) - 2] . '.' . $subs[count($subs) - 1];

        $file = "http://" . $domain . "/favicon.ico";
        $file_headers = @get_headers($file);

        if ($file_headers[0] == 'HTTP/1.1 404 Not Found' || $file_headers[0] == 'HTTP/1.1 404 NOT FOUND' || $file_headers[0] == 'HTTP/1.1 301 Moved Permanently') {

            $fileContent = @file_get_contents("http://" . $domain);

            $dom = new \DOMDocument();
            @$dom ->loadHTML($fileContent);
            $xpath = new \DOMXpath($dom);

            $elements = $xpath->query("head/link//@href");

            $hrefs = array();

            foreach ($elements as $link) {
                $hrefs[] = $link->value;
            }

            $found_favicon = array();
            foreach ($hrefs as $key => $value) {
                if (substr_count($value, 'favicon.ico') > 0) {
                    $found_favicon[] = $value;
                    $icon_key = $key;
                }
            }

            $found_http = array();
            foreach ($found_favicon as $key => $value) {
                if (substr_count($value, 'http') > 0) {
                    $found_http[] = $value;
                    $favicon = $hrefs[$icon_key];
                    $method = "xpath";
                } else {
                    $favicon = $domain . $hrefs[$icon_key];
                    if (substr($favicon, 0, 4) != 'http') {
                        $favicon = 'http://' . $favicon;
                        $method = "xpath+http";
                    }
                }
            }

            if (isset($favicon)) {
                if (!$this->CheckImageExists($favicon)) {
                    $favicon = $fallback_favicon;
                    $method = "fallback";
                }
            } else {
                $favicon = $fallback_favicon;
                $method = "fallback";
            }
        } else {
            $favicon = $file;
            $method = "classic";

            if (!$this->CheckImageExists($file)) {
                $favicon = $fallback_favicon;
                $method = "fallback";
            }
        }
        print_r("FAVICON: ".$favicon."\n");

        return $favicon;

        } catch(\Exception $e){
            return "";
        }
    }


    public function handle()
    {
        $new_url = "";
        $sources = \App\Models\Source::whereNull("logo")->get();

        foreach ($sources as $source) {
            $new_url = $source->url;
            if (strpos($new_url, 'http') === false) {
                $source->url = "https://".$new_url;
                $source->save();
            }
            // if ($source->logo == "") {
            if (1==1 and strpos($source->url, 'google.com') === false) {

                print($source->url);

                $url = $source->url;
                $curl       = curl_init($url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
                curl_setopt($curl, CURLOPT_TIMEOUT, 5);
                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/534.10 (KHTML, like Gecko) Chrome/8.0.552.224 Safari/534.10');
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); //untuk https
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); //untuk https
                //curl_setopt($curl, CURLOPT_ENCODING , 'gzip');
                $html = curl_exec($curl);
                if (curl_error($curl)) {
                    echo 'Curl error: ' . curl_error($curl);
                    $result = ''; //return empty if error
                } else {
                    $result = $html;
                }

                $logo = "";

                if ($result != "") {

                    $dom = new \DOMDocument();
                    @$dom->loadHTML($result);
                    $favicon = $this->getFavicon($url , $dom);
                    if ($favicon != "") {
                        $logo = $favicon;
                    } else {


                        $xpath = new \DOMXPath($dom);

                        //get all images
                        $images = $xpath->query('//img/@src');
                        $img = array();

                        foreach ($images as $image) {
                            $img[] = $image->nodeValue;
                            if (strpos($image->nodeValue, 'logo') !== false and strpos($image->nodeValue, 'data:image') === false) {
                                $urlData = parse_url($source->url);
                                if (strpos($image->nodeValue, 'http') !== false) {
                                    $logo = $image->nodeValue;
                                } else {
                                    if (array_key_exists("host", $urlData)) {
                                        $logo = $urlData['host'] . $image->nodeValue;
                                    }
                                }
                            }
                        }

                        if ($logo == "" and count($images) > 0) {
                            foreach ($images as $image) {
                                $img[] = $image->nodeValue;
                                if (strpos($image->nodeValue, 'data:image') === false) {
                                    if (strpos($image->nodeValue, 'http') !== false) {
                                        $logo = $image->nodeValue;
                                    } else {
                                        if (array_key_exists("host", $urlData)) {
                                            $logo = $urlData['host'] . $image->nodeValue;
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if ($logo != "") {
                        print_r("Source ".$source->name." ".$logo."\n\n");
                        $source->logo = $logo;
                        $source->save();
                    }
                }
            }
        }
    }
}
