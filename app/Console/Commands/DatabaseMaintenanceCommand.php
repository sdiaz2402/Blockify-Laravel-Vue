<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;
use MimeMailParser\Parser;
use Carbon\Carbon;
use Log;
use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use Google_Client;
use Google_Service_Calendar;
use Feeds;


class DatabaseMaintenanceCommand extends Command

{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database_mantainance';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Receive RSS and create DB entries';

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
     * @return mixed
     */

    ///opt/cpanel/ea-php71/root/usr/bin/php -q /home/iotaf/agora.iotaf.com/artisan email_parse

    public function handle()
    {


        $to_archive = \App\Models\Headline::where('created_at', '<', Carbon::now()->subDays(14)->toDateTimeString())->where("created_at", "!=", "0000-00-00 00:00:00")->get();
        foreach ($to_archive as $headline) {
            try {
                $array = $headline->toArray();
                if (!\App\Models\HeadlineArchive::find($headline->id)) {
                    $id = \App\Models\HeadlineArchive::insertGetId($array);
                    if (empty($id)) {

                        Log::error('Failed to insert row into database.');
                        Log::error(DB::getQueryLog());
                        Log::error($array);
                    } else {
                        $headline->forceDelete();
                    }
                }
            } catch (Exception $exception) { }
        }

        $to_delete = array();

        \App\Models\HeadlineArchive::orderBy("id", "DESC")->chunk(5000, function ($objs) {
            foreach ($objs as $object) {
                $old_date = new Carbon($object->created_at);
                $new_date = Carbon::now();
                $months = $old_date->diffInMonths($new_date);
                $diff = $months % 6;
                if ($diff > 5) $diff = 5;
                if ($diff != 0) {
                    $var_name = "\\App\Models\\HeadlineArchive" . $diff;
                    $var_object = new $var_name;
                    if (!$var_object->find($object->id)) {
                        $array = $object->toArray();
                        $var_name = "\\App\Models\\HeadlineArchive" . $diff;
                        $var_object = new $var_name;
                        $id = $var_object->insertGetId($array);

                        if ($var_object->find($object->id)) {
                            $object->forceDelete();
                        }
                    }
                }
            }
        });


        \App\Models\HeadlineGarbage::orderBy("id", "DESC")->chunk(5000, function ($objs) {
            foreach ($objs as $object) {
                $old_date = new Carbon($object->created_at);
                $new_date = Carbon::now();
                $months = $old_date->diffInMonths($new_date);
                $diff = $months % 6;
                if ($diff > 5) $diff = 5;
                if ($diff != 0) {
                    $var_name = "\\App\Models\\HeadlineGarbage" . $diff;
                    $var_object = new $var_name;
                    if (!$var_object->find($object->id)) {
                        $array = $object->toArray();
                        $var_name = "\\App\Models\\HeadlineGarbage" . $diff;
                        $var_object = new $var_name;
                        $id = $var_object->insertGetId($array);

                        if ($var_object->find($object->id)) {
                            $object->forceDelete();
                        }
                    }
                }
            }
        });
    }
}
