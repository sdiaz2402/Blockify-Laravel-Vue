<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Log;
use Illuminate\Database\Eloquent\SoftDeletes;


class InstrumentGroup extends Model
{
    //
    use SoftDeletes;
    // protected $dateFormat = 'U';

    protected $columns = [];

    public function __construct()
    {
        parent::__construct();

    }

    public static function getOrInsert($sym) {

    	$instrument = self::where("name",$sym)->orWhere(function($query) use ($sym) {
            $query->orWhere("acronyms","=",$sym);
            $query->orWhere("acronyms","like","%".$sym."%,");
            $query->orWhere("acronyms","like",",%".$sym."%,");
            $query->orWhere("acronyms","like",",%".$sym."%");
        })->first();

    	if(!$instrument){
    		$instrument = new InstrumentGroup;
    		$instrument->name = $sym;
    		$instrument->save();
    	}

    	return $instrument;
    }

    public function instrumentGroupsFilter(){
        return $this->hasOne("App\Models\InstrumentgroupsFilter","instrument_group_id","id");
    }




}
