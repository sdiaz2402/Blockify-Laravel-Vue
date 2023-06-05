<?php

namespace App\Providers;

use Hamcrest\Type\IsNumeric;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);
        \Pine\BladeFilters\BladeFilters::macro('color_number_style', function ($number, $inverse = false) {
            if($number){
                if(!is_numeric($number)){
                    return "";
                } else {
                    if($inverse){
                        $number = $number * -1;
                    }
                    if($number > 0){
                        return "color:#148d25";
                        return "color:#148d25!important";
                    } else if($number < 0){
                        return "color:#f55d5d";
                        return "color:#f55d5d!important";
                    } else {
                        return "";
                    }
                }
            }
        });

        \Pine\BladeFilters\BladeFilters::macro('range_percentage', function ($number, $open = 1) {
            if($number){
                if(!is_numeric($number)){
                    return "";
                } else {
                    $result = ($number - $open)/$open;
                    $result = $result*100;
                    $result = round($result,2);
                    return $result;
                }
            }
        });

        \Pine\BladeFilters\BladeFilters::macro('format_percentage', function ($value, $plus = false) {
            $output = null;
            if (!is_numeric($value)) {
                $output =  $value;
            } else {
                $output = $value * 100;

                if (abs($output) > 10) {
                    $output = round($output, 0);
                } else if (abs($output) > 10) {
                    $output = round($output,1);
                } else {
                    $output = round($output,2);
                }

                $parts = explode(".", $output);
                // $parts[0] = $parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                $output = implode(".", $parts);

                if ($plus) {
                    if ($value > 0) {
                        $output = "+" . $output;
                    } else if ($value < 0) {
                        // output = "-"+output;
                    }
                }
                return $output;
            }
        });

        \Pine\BladeFilters\BladeFilters::macro('format_price', function ($value, $plus = false, $dollar = falase, $thousands = false) {
            $acro = "";
            $output  = "";
            if (abs($value) > 1000000000000) {
                $value = $value / 1000000000000;
                $value = round($value, 1);
                $acro = "T";
            } else if (abs($value) > 1000000000) {
                $value = $value / 1000000000;
                if ($value > 10) {
                    $value = round($value, 0);
                } else {
                    $value = round($value, 1);
                }
                $acro = "B";
            } else if (abs($value) > 1000000) {

                $value = $value / 1000000;
                if ($value > 10) {
                    $value = round($value, 0);
                } else {
                    $value = round($value, 1);
                }

                $acro = "M";
            } else if (abs($value) > 1000) {

                if ($thousands) {
                    $value = $value / 1000;
                    if ($value > 10) {
                        $value = round($value, 0);
                    } else {
                        $value = round($value, 2);
                    }
                    $acro = "k";
                } else {
                    $value = round($value, 0);
                }
            } else if (abs($value) > 100) {
                $value =  round($value, 0);
            } else if (abs($value) > 10) {
                $value =  round($value, 2);
            } else if (abs($value) > 1) {
                $value =  round($value, 2);
            } else {
                $value =  round($value, 4);
            }
            $parts = explode(".", $value);
            // $parts[0] = $parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");

            $output = implode(".", $parts) . $acro;

            if ($dollar) {
                $output = "$" . $output;
            }

            if ($plus) {
                if ($value > 0) {
                    $output = "+" . $output;
                } else if ($value < 0) {
                    // output = "-"+output;
                }
            }


            return $output;
        });
    }
}
