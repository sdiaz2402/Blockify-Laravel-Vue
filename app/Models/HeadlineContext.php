<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Log;
use Illuminate\Database\Eloquent\SoftDeletes;


class HeadlineContext extends Model
{
    //
    use SoftDeletes;
    // protected $dateFormat = 'U';

    protected $columns = [];

    public function __construct()
    {
        parent::__construct();

    }





}
