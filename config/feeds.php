<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cache Location
    |--------------------------------------------------------------------------
    |
    | Filesystem path to use for caching, the default should be acceptable in
    | most cases.
    |
    */
    'cache.location'           => storage_path('framework/cache'),

    /*
    |--------------------------------------------------------------------------
    | Cache Life
    |--------------------------------------------------------------------------
    |
    | Life of cache, in seconds
    |
    */
    'cache.life'               => 1,

    /*
    |--------------------------------------------------------------------------
    | Cache Disabled
    |--------------------------------------------------------------------------
    |
    | Whether to disable the cache.
    |
    */
    'cache.disabled'           => true,

    /*
    |--------------------------------------------------------------------------
    | Disable Check for SSL certificates (enable for self signed certificates)
    |--------------------------------------------------------------------------
    |
    |
    |
    */
    'ssl_check.disabled'       => true,

    /*
    |--------------------------------------------------------------------------
    | Strip Html Tags Disabled
    |--------------------------------------------------------------------------
    |
    |
    |
    */
    'strip_html_tags.disabled' => true,

    /*
    |--------------------------------------------------------------------------
    | Stripped Html Tags
    |--------------------------------------------------------------------------
    |
    |
    |
    */
    'strip_html_tags.tags'     => [
        'base', 'blink', 'body', 'doctype', 'embed', 'font', 'form', 'frame', 'frameset', 'html', 'iframe', 'input',
        'marquee', 'meta', 'noscript', 'object', 'param', 'script', 'style',
    ],

    /*
    |--------------------------------------------------------------------------
    | Strip Attributes Disabled
    |--------------------------------------------------------------------------
    |
    |
    |
    */
    'strip_attribute.disabled' => false,

    /*
    |--------------------------------------------------------------------------
    | Stripped Attributes Tags
    |--------------------------------------------------------------------------
    |
    |
    |
    */
    'strip_attribute.tags'    => [
        'bgsound', 'class', 'expr', 'id', 'style', 'onclick', 'onerror', 'onfinish', 'onmouseover', 'onmouseout',
        'onfocus', 'onblur', 'lowsrc', 'dynsrc',
    ],

    /*
    |--------------------------------------------------------------------------
    | CURL Options
    |--------------------------------------------------------------------------
    |
    | Array of CURL options (see curl_setopt())
    | Set to null to disable
    |
    */
    'curl.options' => array(
        // CURLOPT_PROXY=>"zproxy.lum-superproxy.io:22225",
        // CURLOPT_PROXYUSERPWD=>"lum-customer-cryptoterminal-zone-residential:Explore10",
        // CURLOPT_PROXYUSERPWD=>"lum-customer-cryptoterminal-zone-residential:Explore10",
        // CURLOPT_PROXYUSERPWD=>"lum-customer-cryptoterminal-zone-zone1-country-us:Explore10",
        // CURLOPT_PROXYTYPE=>CURLPROXY_HTTP,
        CURLOPT_FOLLOWLOCATION=>true,
        CURLOPT_SSL_VERIFYPEER=>false,
        CURLOPT_RETURNTRANSFER=>true,
        CURLOPT_SSL_VERIFYHOST=>false,
        CURLOPT_VERBOSE=>1,
        CURLOPT_TIMEOUT=>30,
        CURLOPT_MAXREDIRS=>5,
        CURLOPT_PROXYUSERPWD=>"user-71656:Explore10"
    ),

    'curl.timeout' => 30,

    'user_agent' => "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.52 Safari/537.17",

];
