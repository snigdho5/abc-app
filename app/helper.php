<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
date_default_timezone_set('Asia/Kolkata'); //Change as per your default time
if (!function_exists('saveQueryLog')) {

    function saveQueryLog($data) {
        DB::table('abc_db_log')->insert(
                ['subject_id' => $data['subject_id'],
                    'user_id' => $data['user_id'],
                    'table_used' => $data['table_used'],
                    'description' => $data['description'],
                    'data_prev' => $data['data_prev'],
                    'data_now' => $data['data_now'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'ip_address' => $_SERVER['REMOTE_ADDR']
                ]
        );
    }

}

if (!function_exists('assocToStringConversion')) {

    function assocToStringConversion($input) {
        if (is_array($input)) {
            $output = implode(', ', array_map(
                            function ($v, $k) {
                        return sprintf("%s='%s'", $k, $v);
                    }, $input, array_keys($input)
            ));
            return $output;
        }
    }

}

if (!function_exists('debug')) {

    function debug($input) {
        echo "<pre>";
        print_r($input);
    }

}

if (!function_exists('getLocationId')) {

    function getLocationId($locnm) {

        return DB::table('abc_ms_location')->select('loc_id')
                        ->where('loc_name', $locnm)
                        ->first();
    }

}

if (!function_exists('getLocationName')) {

    function getLocationName($locid) {

        $data = DB::table('abc_ms_location')->select('loc_name')
                ->where('loc_id', $locid)
                ->first();
        if ($data) {
            return $data;
        } else {
            return false;
        }
    }

}




if (!function_exists('getNotificationAdmin')) {

    function getNotificationAdmin() {

        if (Auth::guard('admin')->user()->admin_role == 1) {
            $data['notidata'] = DB::table('abc_booking')->select('booking_id', 'created_at', 'user_name')
                            ->whereIn('book_status', [2, 3])
                            ->limit(10)
                            ->orderby('created_at', 'DESC')
                            ->get()->toArray();

            $data['noticount'] = DB::table('abc_booking')->select('booking_id', 'created_at', 'user_name')
                            ->whereIn('book_status', [2, 3])
                            ->get()->count();
        } else {
            $centreData = DB::table('abc_manager_centre_tag')
                    ->select('cid')
                    ->where('mid', Auth::guard('admin')->user()->id)
                    ->get();
            $centre_data = [];
            foreach ($centreData as $key => $value) {
                array_push($centre_data, $value->cid);
            }
            $data['notidata'] = DB::table('abc_booking')->select('booking_id', 'created_at', 'user_name')->whereIn('centre_id', $centre_data)
                            ->whereIn('book_status', [2, 3])
                            ->limit(10)
                            ->orderby('created_at', 'DESC')
                            ->get()->toArray();

            $data['noticount'] = DB::table('abc_booking')->select('booking_id', 'created_at', 'user_name')->whereIn('centre_id', $centre_data)
                            ->whereIn('book_status', [2, 3])
                            ->get()->count();
        }
        return $data;
    }

}

if (!function_exists('get_time_difference_php')) {

    function get_time_difference_php($created_time) {
        date_default_timezone_set('Asia/Kolkata'); //Change as per your default time
        $str = strtotime($created_time);
        $today = strtotime(date('Y-m-d H:i:s'));

// It returns the time difference in Seconds...
        $time_differnce = $today - $str;

// To Calculate the time difference in Years...
        $years = 60 * 60 * 24 * 365;

// To Calculate the time difference in Months...
        $months = 60 * 60 * 24 * 30;

// To Calculate the time difference in Days...
        $days = 60 * 60 * 24;

// To Calculate the time difference in Hours...
        $hours = 60 * 60;

// To Calculate the time difference in Minutes...
        $minutes = 60;

        if (intval($time_differnce / $years) > 1) {
            return intval($time_differnce / $years) . " years ago";
        } else if (intval($time_differnce / $years) > 0) {
            return intval($time_differnce / $years) . " year ago";
        } else if (intval($time_differnce / $months) > 1) {
            return intval($time_differnce / $months) . " months ago";
        } else if (intval(($time_differnce / $months)) > 0) {
            return intval(($time_differnce / $months)) . " month ago";
        } else if (intval(($time_differnce / $days)) > 1) {
            return intval(($time_differnce / $days)) . " days ago";
        } else if (intval(($time_differnce / $days)) > 0) {
            return intval(($time_differnce / $days)) . " day ago";
        } else if (intval(($time_differnce / $hours)) > 1) {
            return intval(($time_differnce / $hours)) . " hours ago";
        } else if (intval(($time_differnce / $hours)) > 0) {
            return intval(($time_differnce / $hours)) . " hour ago";
        } else if (intval(($time_differnce / $minutes)) > 1) {
            return intval(($time_differnce / $minutes)) . " minutes ago";
        } else if (intval(($time_differnce / $minutes)) > 0) {
            return intval(($time_differnce / $minutes)) . " minute ago";
        } else if (intval(($time_differnce)) > 1) {
            return intval(($time_differnce)) . " seconds ago";
        } else {
            return "few seconds ago";
        }
    }

}


if (!function_exists('reduceWords')) {

    function reduceWords($str) {
        $out = strlen($str) > 77 ? substr($str, 0, 77) . "..." : $str;
        return $out;
    }

}

if (!function_exists('getPendingRequests')) {

    function getPendingRequest() {

        if (Auth::guard('admin')->user()->admin_role == 1) {
            $pendingData = DB::table('abc_booking')->select('booking_id', 'booking_code', 'user_name', 'user_phone', 'created_at', 'tot_book_amnt', 'book_status')
                            ->whereIn('book_status', [2, 3])
                            ->orderby('created_at', 'DESC')
                            ->limit(6)
                            ->get()->toArray();
        } else {
            $centreData = DB::table('abc_manager_centre_tag')
                    ->select('cid')
                    ->where('mid', Auth::guard('admin')->user()->id)
                    ->get();
            $centre_data = [];
            foreach ($centreData as $key => $value) {
                array_push($centre_data, $value->cid);
            }
            $pendingData = DB::table('abc_booking')->select('booking_id', 'booking_code', 'user_name', 'user_phone', 'created_at', 'tot_book_amnt', 'book_status')
                            ->whereIn('book_status', [2, 3])
                            ->whereIn('centre_id', $centre_data)->orderby('created_at', 'DESC')->limit(6)->get()->toArray();
        }

        return $pendingData;
    }

}

if (!function_exists('getTotalCustCount')) {

    function getTotalCustCount() {
        Carbon::setWeekStartsAt(Carbon::SUNDAY);
        Carbon::setWeekEndsAt(Carbon::SATURDAY);
        if (Auth::guard('admin')->user()->admin_role == 1) {



            $data['count'] = DB::table('abc_ms_cust')->select('cust_id')->where('comp_flag', 0)->get()->count();

            $data['prevmonth'] = DB::table('abc_ms_cust')->where('comp_flag', 0)->whereMonth(
                            'created_at', '=', Carbon::now()->subMonth()->month
                    )->get()->count();

            $data['currmonth'] = DB::table('abc_ms_cust')->where('comp_flag', 0)->whereMonth(
                            'created_at', '=', Carbon::now()->month
                    )->get()->count();

            $data['currweek'] = DB::table('abc_ms_cust')->where('comp_flag', 0)->
                            whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                            ->get()->count();
            $data['prevweek'] = DB::table('abc_ms_cust')->where('comp_flag', 0)->
                            whereBetween('created_at', [Carbon::today()->subWeek(), Carbon::now()->startOfWeek()])
                            ->get()->count();

            DB::enableQueryLog();
            $dateBeforeSix = strtotime(date('Y-m-d H:i:s') . '-6 months');
            $dateBeforeSixFormatted = date('Y-m-d', $dateBeforeSix);
            $data['appr_cust_count6'] = DB::table('abc_ms_cust')->select('cust_id')->where('comp_flag', 0)->where('cust_status', 1)->whereDate('created_at', '>=', $dateBeforeSixFormatted)->get()->count();
            $data['pend_cust_count6'] = DB::table('abc_ms_cust')->select('cust_id')->where('comp_flag', 0)->where('cust_status', 4)->whereDate('created_at', '>=', $dateBeforeSixFormatted)->get()->count();

            $appr_cust_count6 = [];
            $pend_cust_count6 = [];

            $closedreq = DB::select('select year(created_at) as year,monthname(created_at) AS monthname, month(created_at) as month, count(cust_id) as total_count from abc_ms_cust where comp_flag = 0 AND  cust_status = 1  group by year(created_at), month(created_at)');

            $cancelreq = DB::select('select year(created_at) as year,monthname(created_at) AS monthname, month(created_at) as month, count(cust_id) as total_count from abc_ms_cust where comp_flag = 0 AND  cust_status = 4  group by year(created_at), month(created_at)');

            foreach ($closedreq as $key => $value) {
//$closedreqArr[$value->year][$value->month] = $value->total_count;
                array_push($appr_cust_count6, $value->total_count);
            }

            foreach ($cancelreq as $key => $value) {
//$cancelreqArr[$value->year][$value->month] = $value->total_count;
                array_push($pend_cust_count6, $value->total_count);
            }

            $data['appr_cust_count6_arr'] = json_encode(array_reverse(array_pad($appr_cust_count6, 7, 0)));
            $data['pend_cust_count6_arr'] = json_encode(array_reverse(array_pad($pend_cust_count6, 7, 0)));


//dd(DB::getQueryLog());
            //dd($data);

            $data['appr_cust_count'] = DB::table('abc_ms_cust')->select('cust_id')->where('comp_flag', 0)->whereIn('cust_status', [1])->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)->get()->count();
            $data['pend_cust_count'] = DB::table('abc_ms_cust')->select('cust_id')->where('comp_flag', 0)->whereIn('cust_status', [4])->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)->get()->count();
        } else {


            //dd(Auth::guard('admin')->user()->id);

            $centreData = DB::table('abc_manager_centre_tag')
                    ->select('cid')
                    ->where('mid', Auth::guard('admin')->user()->id)
                    ->get();
            $centre_data = [];
            foreach ($centreData as $key => $value) {
                array_push($centre_data, $value->cid);
            }

            $locationdata = DB::table('abc_ms_centre')
                    ->select('location')
                    ->whereIn('centre_id', $centre_data)
                    ->get();

            $loc_data = [];
            foreach ($locationdata as $key => $value) {
                array_push($loc_data, $value->location);
            }


            //$query = DB::table('abc_ms_cust')->select('cust_id')->where('comp_flag',0);

            $data['count'] = DB::table('abc_ms_cust')->select('cust_id')->where('comp_flag', 0)
                            ->whereIn('custloc', $loc_data)->get()->count();


            $data['prevmonth'] = DB::table('abc_ms_cust')->where('comp_flag', 0)->whereMonth(
                            'created_at', '=', Carbon::now()->subMonth()->month
                    )->whereIn('custloc', $loc_data)->get()->count();

            $data['currmonth'] = DB::table('abc_ms_cust')->where('comp_flag', 0)->whereMonth(
                            'created_at', '=', Carbon::now()->month
                    )->whereIn('custloc', $loc_data)->get()->count();

            $data['currweek'] = DB::table('abc_ms_cust')->where('comp_flag', 0)->
                            whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->whereIn('custloc', $loc_data)
                            ->get()->count();
            $data['prevweek'] = DB::table('abc_ms_cust')->where('comp_flag', 0)->
                            whereBetween('created_at', [Carbon::today()->subWeek(), Carbon::now()->startOfWeek()])->whereIn('custloc', $loc_data)
                            ->get()->count();

            $data['appr_cust_count6'] = DB::table('abc_ms_cust')->where('comp_flag', 0)->select('cust_id')->whereIn('cust_status', [1])->whereMonth('created_at', '>=', Carbon::now()->subMonths(6)->month)->whereIn('custloc', $loc_data)->get()->count();
            $data['pend_cust_count6'] = DB::table('abc_ms_cust')->where('comp_flag', 0)->select('cust_id')->whereIn('cust_status', [4])->whereMonth('created_at', '>=', Carbon::now()->subMonths(6)->month)->whereIn('custloc', $loc_data)->get()->count();

            $data['appr_cust_count'] = DB::table('abc_ms_cust')->where('comp_flag', 0)->select('cust_id')->whereIn('cust_status', [1])->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)->whereIn('custloc', $loc_data)->get()->count();
            $data['pend_cust_count'] = DB::table('abc_ms_cust')->where('comp_flag', 0)->select('cust_id')->whereIn('cust_status', [4])->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)->whereIn('custloc', $loc_data)->get()->count();
        }

        $diffmonth = $data['currmonth'] - $data['prevmonth'];
        $data['prevmonth'] = $data['prevmonth'] != 0 ? $data['prevmonth'] : 1;
        if ($diffmonth != 0) {
            $data['perIncr'] = $diffmonth / $data['prevmonth'] * 100;
        } else {
            $data['perIncr'] = 0;
        }
        $diffweek = $data['currweek'] - $data['prevweek'];
        $data['prevweek'] = $data['prevweek'] != 0 ? $data['prevweek'] : 1;
        if ($diffmonth != 0) {
            $data['perDiffWeek'] = $diffweek / $data['prevweek'] * 100;
        } else {
            $data['perDiffWeek'] = 0;
        }
        return $data;
    }

}

if (!function_exists('getTotalReqCount')) {

    function getTotalReqCount() {
        Carbon::setWeekStartsAt(Carbon::SUNDAY);
        Carbon::setWeekEndsAt(Carbon::SATURDAY);
        if (Auth::guard('admin')->user()->admin_role == 1) {
            $data['new_req_count'] = DB::table('abc_booking')->select('booking_id')
                            ->whereIn('book_status', [2, 3])
                            ->get()->count();

            $data['prog_req_count'] = DB::table('abc_booking')->select('booking_id')
                            ->whereIn('book_status', [1])
                            ->get()->count();
            $data['closed_req_count'] = DB::table('abc_booking')->select('booking_id')->whereIn('book_status', [5])->whereMonth('created_at', '=', Carbon::now()->month)->get()->count();
            $data['canceled_req_count'] = DB::table('abc_booking')->select('booking_id')->whereIn('book_status', [4])->whereMonth('created_at', '=', Carbon::now()->month)->get()->count();

            $data['total_req_count'] = DB::table('abc_booking')->select('booking_id')
                            ->where('book_status', '!=', 5)->get()->count();

            $data['prevmonth'] = DB::table('abc_booking')->whereMonth(
                            'created_at', '=', Carbon::now()->subMonth()->month
                    )->get()->count();

            $data['currmonth'] = DB::table('abc_booking')->whereMonth(
                            'created_at', '=', Carbon::now()->month
                    )->get()->count();

            $data['newcurrweek'] = DB::table('abc_booking')->select('booking_id')->
                            whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                            ->whereIn('book_status', [2, 3])
                            ->get()->count();
            $data['newprevweek'] = DB::table('abc_booking')->select('booking_id')->
                            whereBetween('created_at', [Carbon::today()->subWeek(), Carbon::now()->startOfWeek()])
                            ->whereIn('book_status', [2, 3])
                            ->get()->count();

            $data['progcurrweek'] = DB::table('abc_booking')->select('booking_id')->
                            whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                            ->whereIn('book_status', [2, 3])
                            ->get()->count();
            $data['progprevweek'] = DB::table('abc_booking')->select('booking_id')->
                            whereBetween('created_at', [Carbon::today()->subWeek(), Carbon::now()->startOfWeek()])
                            ->whereIn('book_status', [2, 3])
                            ->get()->count();
        } else {

            $centreData = DB::table('abc_manager_centre_tag')
                    ->select('cid')
                    ->where('mid', Auth::guard('admin')->user()->id)
                    ->get();
            $centre_data = [];
            foreach ($centreData as $key => $value) {
                array_push($centre_data, $value->cid);
            }
            $data['new_req_count'] = DB::table('abc_booking')->select('booking_id')
                            ->whereIn('book_status', [2, 3])
                            ->whereIn('centre_id', $centre_data)->get()->count();




            $data['prog_req_count'] = DB::table('abc_booking')->select('booking_id')
                            ->whereIn('book_status', [1])
                            ->whereIn('centre_id', $centre_data)->get()->count();



            $data['closed_req_count'] = DB::table('abc_booking')->select('booking_id')->whereIn('book_status', [5])->whereMonth('created_at', '=', Carbon::now()->month)->whereIn('centre_id', $centre_data)->get()->count();
            $data['canceled_req_count'] = DB::table('abc_booking')->select('booking_id')->whereIn('book_status', [4])->whereMonth('created_at', '=', Carbon::now()->month)->whereIn('centre_id', $centre_data)->get()->count();

            $data['total_req_count'] = DB::table('abc_booking')->select('booking_id')
                            ->whereIn('centre_id', $centre_data)->where('book_status', '!=', 5)->get()->count();

            $data['prevmonth'] = DB::table('abc_booking')->whereMonth(
                            'created_at', '=', Carbon::now()->subMonth()->month
                    )->whereIn('centre_id', $centre_data)->get()->count();

            $data['currmonth'] = DB::table('abc_booking')->whereMonth(
                            'created_at', '=', Carbon::now()->month
                    )->whereIn('centre_id', $centre_data)->get()->count();

            $data['newcurrweek'] = DB::table('abc_booking')->select('booking_id')->
                            whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                            ->whereIn('book_status', [2, 3])
                            ->whereIn('centre_id', $centre_data)->get()->count();
            $data['newprevweek'] = DB::table('abc_booking')->select('booking_id')->
                            whereBetween('created_at', [Carbon::today()->subWeek(), Carbon::now()->startOfWeek()])
                            ->whereIn('book_status', [2, 3])
                            ->whereIn('centre_id', $centre_data)->get()->count();

            $data['progcurrweek'] = DB::table('abc_booking')->select('booking_id')->
                            whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                            ->whereIn('book_status', [2, 3])
                            ->whereIn('centre_id', $centre_data)->get()->count();
            $data['progprevweek'] = DB::table('abc_booking')->select('booking_id')->
                            whereBetween('created_at', [Carbon::today()->subWeek(), Carbon::now()->startOfWeek()])->whereIn('book_status', [2, 3])->whereIn('centre_id', $centre_data)->get()->count();
        }


        $diffmonth = $data['currmonth'] - $data['prevmonth'];
        $data['prevmonth'] = $data['prevmonth'] != 0 ? $data['prevmonth'] : 1;
        if ($diffmonth != 0) {
            $data['perIncr'] = $diffmonth / $data['prevmonth'] * 100;
        } else {
            $data['perIncr'] = 0;
        }


        $newdiffweek = $data['newcurrweek'] - $data['newprevweek'];
        $data['newprevweek'] = $data['newprevweek'] != 0 ? $data['newprevweek'] : 1;
        if ($newdiffweek != 0) {
            $data['newperDiffWeek'] = $newdiffweek / $data['newprevweek'] * 100;
        } else {
            $data['newperDiffWeek'] = 0;
        }
//                dd($data['newperDiffWeek']);
        $progdiffweek = $data['progcurrweek'] - $data['progprevweek'];
        $data['progprevweek'] = $data['progprevweek'] != 0 ? $data['progprevweek'] : 1;
        if ($progdiffweek != 0) {
            $data['progperDiffWeek'] = $newdiffweek / $data['progprevweek'] * 100;
        } else {
            $data['progperDiffWeek'] = 0;
        }
        if (Auth::guard('admin')->user()->admin_role == 1) {
            $closedreq = DB::select('select year(created_at) as year,monthname(created_at) AS monthname, month(created_at) as month, count(booking_id) as total_count from abc_booking where book_status = 5  group by year(created_at), month(created_at)');

            $cancelreq = DB::select('select year(created_at) as year,monthname(created_at) AS monthname, month(created_at) as month, count(booking_id) as total_count from abc_booking where book_status = 4  group by year(created_at), month(created_at)');
        } else {
            $centreArr = implode("','", $centre_data);
            $closedreq = DB::select('select year(created_at) as year,monthname(created_at) AS monthname, month(created_at) as month, count(booking_id) as total_count from abc_booking where book_status = 5 and centre_id IN ("' . $centreArr . '") group by year(created_at), month(created_at)');

            $cancelreq = DB::select('select year(created_at) as year,monthname(created_at) AS monthname, month(created_at) as month, count(booking_id) as total_count from abc_booking where book_status = 4 and  centre_id IN ("' . $centreArr . '") group by year(created_at), month(created_at)');
        }

        $closedreqArr = [];
        $cancelreqArr = [];
        foreach ($closedreq as $key => $value) {
//$closedreqArr[$value->year][$value->month] = $value->total_count;
            array_push($closedreqArr, $value->total_count);
        }

        foreach ($cancelreq as $key => $value) {
//$cancelreqArr[$value->year][$value->month] = $value->total_count;
            array_push($cancelreqArr, $value->total_count);
        }

        $data['closedReqArr'] = json_encode(array_reverse(array_pad($closedreqArr, 7, 0)));
        $data['cancelReqArr'] = json_encode(array_reverse(array_pad($cancelreqArr, 7, 0)));

        return $data;
    }

}


if (!function_exists('rand_pass')) {

    function rand_pass($length) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        return substr(str_shuffle($chars), 0, $length);
    }

}

if (!function_exists('promo_code')) {

    function promo_code($length) {
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        return substr(str_shuffle($chars), 0, $length);
    }

}

if (!function_exists('get_sms')) {

//function for SMS
    function get_sms($url) {
        $url = str_replace("#", "%23", $url);
        $url = str_replace("<", "%26lt;", $url);
        $url = str_replace(">", "%26gt;", $url);
        $options = array(
            CURLOPT_RETURNTRANSFER => true, // return web page
            CURLOPT_HEADER => false, // don't return headers
            CURLOPT_FOLLOWLOCATION => true, // follow redirects
            CURLOPT_ENCODING => "", // handle all encodings
            CURLOPT_USERAGENT => "spider", // who am i
            CURLOPT_AUTOREFERER => true, // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120, // timeout on connect
            CURLOPT_TIMEOUT => 120, // timeout on response
            CURLOPT_MAXREDIRS => 10, // stop after 10 redirects
        );
        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        $content = curl_exec($ch);
        $err = curl_errno($ch);
        $errmsg = curl_error($ch);
        $header = curl_getinfo($ch);
        curl_close($ch);
        $header['errno'] = $err;
        $header['errmsg'] = $errmsg;
        $header['content'] = $content;
        return $header;
    }

}




if (!function_exists('validateImage')) {

    function validateImage($mime) {
        if ($mime == "jpeg" || $mime == "png" || $mime == "jpg" || $mime == "gif" || $mime == "webp" || $mime == "JPG") {
            return true;
        } else {
            return false;
        }
    }

}




if (!function_exists('getCatName')) {

    function getCatName($catid) {

        $catData = DB::table('abc_ms_category')->select('acat_name')
                ->where('acat_id', $catid)
                ->first();

        if ($catData->acat_name)
            return $catData->acat_name;
        else
            return false;
    }

}
if (!function_exists('getLocName')) {

    function getLocName($locid) {

        $catData = DB::table('abc_ms_location')->select('loc_name')
                ->where('loc_id', $locid)
                ->first();

        return $catData->loc_name;
    }

}




if (!function_exists('getCategoryIdByName')) {

    function getCategoryIdByName($type) {
        $catData = DB::table('abc_ms_category')->select('acat_id')
                ->where('acat_name', 'like', '%' . $type . '%')
                ->where('acat_type', 'Service')
                ->where('acat_status', 1)
                ->first();
        if ($catData) {
            return $catData->acat_id;
        } else {
            return false;
        }
    }

}



if (!function_exists('getCentreName')) {

    function getCentreName($cid) {
        $catData = DB::table('abc_ms_centre')->select('centre', 'location')
                ->where('centre_id', $cid)
                ->first();

        if ($catData) {
            return $catData->centre . ' ( ' . getLocName($catData->location) . ')';
        } else {
            return false;
        }
    }

}

if (!function_exists('getCentreForManager')) {

    function getCentreForManager($mid) {
        $catData = DB::table('abc_manager_centre_tag')->select('cid')
                ->where('mid', $mid)
                ->get();

        if ($catData) {
            $centreHtml = '';
            foreach ($catData as $key => $value) {
                $centreHtml .= getCentreName($value->cid) . ', ';
            }
            $centreHtml = rtrim($centreHtml, ', ');
            return $centreHtml;
        } else {
            return false;
        }
    }

}

if (!function_exists('getCentreLocation')) {

    function getCentreLocation($cid) {
        $catData = DB::table('abc_ms_centre')->select('location')
                ->where('centre_id', $cid)
                ->first();

        if ($catData) {
            return $catData->location;
        } else {
            return false;
        }
    }

}
if (!function_exists('getSupportName')) {

    function getSupportName($sid) {
        $catData = DB::table('abc_ms_sprtserv')->select('ss_text')
                ->where('ss_id', $sid)
                ->first();

        if ($catData) {
            return $catData->ss_text;
        } else {
            return false;
        }
    }

}

if (!function_exists('getCustName')) {

    function getCustName($cemail) {
        $catData = DB::table('abc_ms_cust')->select('cust_id')
                ->where('cust_email', $cemail)
                ->first();
        if ($catData) {
            return $catData->cust_id;
        } else {
            return false;
        }
    }

}
if (!function_exists('getCatIdByService')) {

    function getCatIdByService($rid) {
        $catData = DB::table('abc_room_rate')->select('ms_cat')
                ->where('rr_id', $rid)
                ->first();
        if ($catData) {
            return $catData->ms_cat;
        } else {
            return false;
        }
    }

}

if (!function_exists('getServiceIncludes')) {

    function getServiceIncludes($bid) {
        $serNameArr = array();
        $catData = DB::table('abc_booking_details')->select('service_name')
                ->where('booking_id', $bid)
                ->get();
        if (count($catData) > 0) {
            foreach ($catData as $key => $value) {
                array_push($serNameArr, $value->service_name);
            }
            return implode(',', $serNameArr);
        } else {
            return false;
        }
    }

}


if (!function_exists('getServicePackageName')) {

    function getServicePackageName($bid) {

        $catData = DB::table('abc_booking_details')->select('service_id')
                ->where('booking_id', $bid)
                ->first();

        if ($catData) {
            return $catData->service_id;
        } else {
            return false;
        }
    }

}



if (!function_exists('ordinal')) {

    function ordinal($number) {
        $ends = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
        if ((($number % 100) >= 11) && (($number % 100) <= 13))
            return $number . 'th';
        else
            return $number . $ends[$number % 10];
    }

}

if (!function_exists('getCompNameById')) {

    function getCompNameById($rid) {
        $catData = DB::table('abc_ms_cust')->select('cust_comp')
                ->where('cust_id', $rid)
                ->where('comp_flag', 1)
                ->first();
        if ($catData) {
            return $catData->cust_comp;
        } else {
            return false;
        }
    }

}

if (!function_exists('getCatNameById')) {

    function getCatNameById($rid) {
        $catData = DB::table('abc_ms_category')->select('acat_name')
                ->where('acat_id', $rid)
                ->where('acat_status', 1)
                ->first();
        if ($catData) {
            return $catData->acat_name;
        } else {
            return false;
        }
    }

}

if (!function_exists('getMsInfoDefaultPrice')) {

    function getMsInfoDefaultPrice($id) {
        $catData = DB::table('abc_ms_info')->select('ms_name', 'ms_hour', 'ms_half', 'ms_full', 'ms_month', 'ms_quart', 'ms_hy', 'ms_year')
                ->where('ms_id', $id)
                ->first();
        if ($catData) {
            return $catData;
        } else {
            return false;
        }
    }

}


if (!function_exists('getConfigNameById')) {

    function getConfigNameById($rid) {
        $catData = DB::table('abc_ms_info')->select('ms_name', 'ms_type')
                ->where('ms_id', $rid)
                ->where('ms_status', 1)
                ->first();
        if ($catData) {
            if ($catData->ms_type != 0) {
                return $catData->ms_name . ' (' . $catData->ms_type . ' seater)';
            } else {
                return $catData->ms_name;
            }
        } else {
            return false;
        }
    }

}

if (!function_exists('getSupportServiceNameById')) {

    function getSupportServiceNameById($rid) {
        $catData = DB::table('abc_ms_sprtserv')->select('ss_text')
                ->where('ss_id', $rid)
                ->where('ss_status', 1)
                ->first();
        if ($catData) {
            return $catData->ss_text;
        } else {
            return false;
        }
    }

}

if (!function_exists('getCustName1')) {

    function getCustName1($cid) {
        $catData = DB::table('abc_ms_cust')->select('cust_nme')
                ->where('cust_id', $cid)
                ->first();
        if ($catData) {
            return $catData->cust_nme;
        } else {
            return false;
        }
    }

}

if (!function_exists('getPackageName')) {

    function getPackageName($sid) {
        $catData = DB::table('abc_ms_info')->select('ms_name')
                ->where('ms_id', $sid)
                ->first();
        if ($catData) {
            return $catData->ms_name;
        } else {
            return false;
        }
    }

}


if (!function_exists('getCompanyName')) {

    function getCompanyName($sid) {
        $catData = DB::table('abc_client_comp')->select('cc_name')
                ->where('cc_id', $sid)
                ->first();
        if ($catData) {
            return $catData->cc_name;
        } else {
            return false;
        }
    }

}


if (!function_exists('getPlanName')) {

    function getPlanName($pid) {
        switch ($pid) {
            case(2):
                return "Full day";
            case(5):
                return "Hourly";
            case(3):
                return "Monthly";
            case(8):
                return "Quarterly";
            case(9):
                return "Half yearly";
            case(4):
                return "Yearly";
            Default:
                break;
        }
    }

}
if (!function_exists('csvToArray')) {

    function csvToArray($filename = '', $delimiter = ',') {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        return $data;
    }

}
if (!function_exists('get_hours_range')) {

    function get_hours_range($start = 0, $end = 86400, $step = 1800, $format = 'g:i a') {
        $hour = date('H');
        $minute = date('i');
        $rounded = date('H:i:s', ceil(strtotime(date('H:i:s')) / 1800) * 1800);
        $time = explode(':', $rounded);
        $new_start = ($time[0] * 3600) + ($time[1] * 60);
        //$minute = ($minute < 30 || $minute > 30) ? 30 : $minute;
        //$new_start = (($hour * 60) + $minute) * 60;
        $times = array();
        foreach (range($start, $end, $step) as $timestamp) {
            $hour_mins = gmdate('H:i', $timestamp);
            if (!empty($format))
                $times[$hour_mins] = gmdate($format, $timestamp);
            else
                $times[$hour_mins] = $hour_mins;
        }
        return $times;
    }

}

if (!function_exists('prepare_push_noti')) {

// prepare push notification
    function prepare_push_noti($pn_image, $message, $headln_tit, $uid, $appURls, $andrd_id) {
        $pushsiteurl = "https://getquote.apeejaybusinesscentre.com/abcapp/app/";
        $pushcpath = '/var/www/websites/getquote.apeejaybusinesscentre.com/abcapp/app/';

        $err = '';

        $tcnt = 0;

        if (isset($message) && $message != '') {

            $murl = '';

            if ($pn_image != '' && file_exists($pushcpath . "upload/pushn/" . $pn_image)) {
                $murl = $pushsiteurl . "upload/pushn/" . $pn_image;
            }
            $pushMessage = $message;
//ANDROID	
            $uData = DB::table('gcm_users')
                            ->select('gcm_regid')
                            ->join('abc_ms_cust', 'abc_ms_cust.cust_mobile', '=', 'gcm_users.email')
                            ->where('gcm_users.email', $uid)
                            ->orderBy('gcm_users.created_at', 'DESC')
                            ->get()->toArray();

            if (count($uData) > 0) {
                for ($i = 0; $i < count($uData); $i++) {
                    if ($uData[$i]->gcm_regid != '') {
                        $regIds = array($uData[$i]->gcm_regid);
                        $mTitle = 'ABC';
                        if (isset($headln_tit) && $headln_tit != '')
                            $mTitle = $headln_tit;
                        $AppNavg = '';
                        if (isset($appURls))
                            $AppNavg = $appURls;
                        if ($murl != '') {
                            $message = array('message' => $pushMessage, 'title' => $mTitle, 'style' => 'picture', 'picture' => $murl, 'summaryText' => $pushMessage, 'sound' => 'notify', 'appurl' => $AppNavg);
                        } else {
                            $message = array('message' => $pushMessage, 'title' => $mTitle, 'sound' => 'notify', 'appurl' => $AppNavg);
                        }

                        //dd($message);
                        $result = send_push_notification($regIds, $message);
                        $json = json_decode($result, true);
                        $result = "multicast_id: " . $json['multicast_id'] . "success: " . $json['success'] . "failure: " . $json['failure'];
                        $notify_succ = $json['success'];
                        $notify_error = $json['failure'];
                        $notification_status = '1';
                        $dataToInsert = array('pn_msg' => $result, 'user_id' => $uid, 'gcm_id' => $uData[$i]->gcm_regid, 'cmpn_id' => $andrd_id, 'pn_date' => date('Y-m-d H:i:s'), 'pn_type' => 'ANDROID', 'notify_succ' => $notify_succ, 'notify_error' => $notify_error, 'notification_status' => $notification_status);

                        $logInsert = DB::table('cron_env_pnstatus')->insert($dataToInsert);
                        if ($logInsert) {
                            $tcnt = $tcnt + 1;
                        }
                    }
                } // else {  }
            }
//ANDROID
//ios
            $uData = DB::table('ios_users')
                            ->select('gcm_regid')
                            ->join('abc_ms_cust', 'abc_ms_cust.cust_mobile', '=', 'ios_users.email')
                            ->where('ios_users.email', $uid)
                            ->orderBy('ios_users.created_at', 'DESC')
                            ->get()->toArray();
            if (count($uData) > 0) {
                for ($i = 0; $i < count($uData); $i++) {
                    if ($uData[$i]->gcm_regid != '') {
                        $regiss = $uData[$i]->gcm_regid; //'0e18d213c67f39ce76811c5bef55fc2465607043382f04145bb1b1c5ab50d3f9'
                        $mTitle = 'ABC';
                        if (isset($headln_tit) && $headln_tit != '')
                            $mTitle = $headln_tit;
                        $AppNavg = '';
                        if (isset($appURls))
                            $AppNavg = $appURls;
                        $message = array('alert' => $pushMessage, 'badge' => '1', 'sound' => 'notify', 'picture' => $murl, 'title' => $mTitle, 'appurl' => $AppNavg);
                        $result = send_ios_push_notification($regiss, $message);
                        $notify_succ = $result['notify_succ'];
                        $notify_error = $result['notify_error'];
                        $notification_status = $result["notification_status"];
                        $message = $result["message"];
//$dataToInsert = array('pn_msg' => $result, 'user_id' => $uid, 'cmpn_id' => $andrd_id, 'pn_date' => date('Y-m-d H:i:s'), 'pn_type' => 'iOS');
                        $dataToInsert = array('pn_msg' => $message, 'user_id' => $uid, 'gcm_id' => $regiss, 'cmpn_id' => $andrd_id, 'pn_date' => date('Y-m-d H:i:s'), 'pn_type' => 'iOS', 'notify_succ' => $notify_succ, 'notify_error' => $notify_error, 'notification_status' => $notification_status);
                        $logInsert = DB::table('cron_env_pnstatus')->insert($dataToInsert);
                        if ($logInsert) {
                            $tcnt = $tcnt + 1;
                        }
                    }
                } //else { }
//ios
            }
        }
        return true;
    }

}

if (!function_exists('prepare_push_noti_all')) {

// prepare push notification
    function prepare_push_noti_all($pn_image, $message, $headln_tit, $uid, $appURls, $andrd_id, $type) {


        $err = '';

        $tcnt = 0;

        if (isset($message) && $message != '') {

            $murl = '';

            if ($pn_image != '') {
                $murl = $pn_image;
            }
            $pushMessage = $message;
//ANDROID	
            $mTitle = 'ABC';
            if (isset($headln_tit) && $headln_tit != '')
                $mTitle = $headln_tit;
            $AppNavg = '';
            if (isset($appURls))
                $AppNavg = $appURls;
            if ($murl != '') {
                $message = array('message' => $pushMessage, 'title' => $mTitle, 'style' => 'picture', 'picture' => $murl, 'summaryText' => $pushMessage, 'sound' => 'notify', 'appurl' => $AppNavg);
            } else {
                $message = array('message' => $pushMessage, 'title' => $mTitle, 'sound' => 'notify', 'appurl' => $AppNavg);
            }

            //dd($message);
            $result = send_push_notification_check($message, $type, $andrd_id);


//ANDROID
//ios
            $mTitle = 'ABC';
            if (isset($headln_tit) && $headln_tit != '')
                $mTitle = $headln_tit;
            $AppNavg = '';
            if (isset($appURls))
                $AppNavg = $appURls;
            $message = array('alert' => $pushMessage, 'badge' => '1', 'sound' => 'notify', 'picture' => $murl, 'title' => $mTitle, 'appurl' => $AppNavg);
            send_ios_push_notification_check($message, $type, $andrd_id);
        }
        return true;
    }

}

if (!function_exists('send_ios_push_notification_check')) {

//Sending Push Notification
    function send_ios_push_notification_check($message, $type, $andrd_id) {
       /* $uData = '';
        $allUserData = '';
        if ($type == 1) {
            $allUserData = DB::table('abc_ms_cust')
                            ->select('cust_mobile')
                            ->where('cust_status', 1)
                            ->orderBy('cust_id', 'DESC')
                            ->get()->toArray();
        } elseif ($type == 2) {
            $allUserData = DB::table('abc_camp_flag')
                            ->select('user_id as cust_mobile', 'id')
                            ->where('ios_send_flag', 0)
                            ->orderBy('created_at', 'DESC')
                            ->get()->toArray();
        }
// Put your private key's passphrase here:
        $passphrase = '123456';
// Put your alert message here:
////////////////////////////////////////////////////////////////////////////////
        $ctx = stream_context_create();
        //stream_context_set_option($ctx, 'ssl', 'local_cert', '/var/www/websites/enviroindia.in/ehs_v1/upload/pushoct20.pem');
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
// Open a connection to the APNS server
        $fp = '';
        $err = '';
        $errstr = '';
        $isLive = DB::table('abc_live_flag')->select('is_live')->first();
        if ($isLive->is_live) {
            ///echo 'LIVE';
            $fp = stream_socket_client(
                    'ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
        } else {
            ///	echo 'TEST';
            $fp = stream_socket_client(
                    'ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
        }
        //Production Server
//LIVE Server
        if (!$fp)
            exit("Failed to connect: $err $errstr" . PHP_EOL);
        $body['aps'] = $message;
        $payload = json_encode($body);
// Build the binary notification
        for ($i = 0; $i < count($allUserData); $i++) {
            $uData = DB::table('ios_users')
                            ->select('gcm_regid', 'ios_users.email')
                            ->join('abc_ms_cust', 'abc_ms_cust.cust_mobile', '=', 'ios_users.email')
                            ->where('ios_users.email', $allUserData[$i]->cust_mobile)
                            ->orderBy('ios_users.created_at', 'DESC')
                            ->get()->toArray();
            //dd($uData[0]->gcm_regid);
            for ($j = 0; $j < count($uData); $j++) {
                if (isset($uData[$j]->gcm_regid)) {
                    $deviceToken = $uData[$j]->gcm_regid;
                    $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
// Send it to the server
                    $result = fwrite($fp, $msg, strlen($msg));
                    if (!$result) {
                        //return 'Message not delivered' . PHP_EOL;
                        $result = array();
                        $result["notify_succ"] = 0;
                        $result["notify_error"] = 1;
                        $result["notification_status"] = 0;
                        $result["message"] = 'Message not delivered';
                        ///return $result;
                    } else {
                        $result = array();
                        $result["notify_succ"] = 1;
                        $result["notify_error"] = 0;
                        $result["notification_status"] = 1;
                        $result["message"] = 'Message delivered';
                        ////return $result;
                    }
                    $notify_succ = $result['notify_succ'];
                    $notify_error = $result['notify_error'];
                    $notification_status = $result["notification_status"];
                    $message = $result["message"];

                    $dataToInsert = array('pn_msg' => $message, 'user_id' => $uData[$j]->email, 'gcm_id' => $uData[$j]->gcm_regid, 'cmpn_id' => $andrd_id, 'pn_date' => date('Y-m-d H:i:s'), 'pn_type' => 'iOS', 'notify_succ' => $notify_succ, 'notify_error' => $notify_error, 'notification_status' => $notification_status);
                    $logInsert = DB::table('cron_env_pnstatus')->insert($dataToInsert);
                    // log insert then update flag
                }
            }
            if ($type == 2) {
                DB::table('abc_camp_flag')->where('id', $allUserData[$i]->id)->update(array('ios_send_flag' => 1));
            }
        }

// Close the connection to the server
        fclose($fp);
    }

///    } localhost condition

        */
        
    }
}

if (!function_exists('send_push_notification_check')) {

    //Sending Push Notification
    function send_push_notification_check($message, $type, $andrd_id) {
        $result = '';
        if ($_SERVER['HTTP_HOST'] != 'localhost') {
            // Set POST variables
            $url = 'https://fcm.googleapis.com/fcm/send'; //android.googleapis.com/gcm/send
            $registatoin_ids = array();
            $uAndrData = array();
            if ($type == 1) {
                $allUserData = DB::table('abc_ms_cust')
                                ->select('cust_mobile')
                                ->where('cust_status', 1)
                                ->orderBy('cust_id', 'DESC')
                                ->get()->toArray();
            } else if ($type == 2) {
                $allUserData = DB::table('abc_camp_flag')
                                ->select('user_id as cust_mobile', 'id')
                                ->where('and_send_flag', 0)
                                ->orderBy('created_at', 'DESC')
                                ->get()->toArray();
            }
            for ($i = 0; $i < count($allUserData); $i++) {
                $uAndrData = DB::table('gcm_users')
                                ->select('gcm_regid', 'gcm_users.email')
                                ->join('abc_ms_cust', 'abc_ms_cust.cust_mobile', '=', 'gcm_users.email')
                                ->where('gcm_users.email', $allUserData[$i]->cust_mobile)
                                ->orderBy('gcm_users.created_at', 'DESC')
                                ->get()->toArray();
                //dd($uAndrData);
                for ($j = 0; $j < count($uAndrData); $j++) {
                    if (isset($uAndrData[$j]->gcm_regid)) {
                        //dd($registatoin_ids);
                        $registatoin_ids = array($uAndrData[$j]->gcm_regid);
                        $fields = array(
                            'registration_ids' => $registatoin_ids,
                            'data' => $message,
                        );

                        $headers = array(
                            'Authorization: key=' . $_ENV['GOOGLE_API_KEY'],
                            'Content-Type: application/json'
                        );
                        //print_r($headers);
                        // Open connection
                        $ch = curl_init();

                        // Set the url, number of POST vars, POST data
                        curl_setopt($ch, CURLOPT_URL, $url);

                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                        // Disabling SSL Certificate support temporarly
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

                        // Execute post
                        $result = curl_exec($ch);
                        /* if ($result === FALSE) {
                          die('Curl failed: ' . curl_error($ch));
                          } */
                        // Close connection
                        curl_close($ch);
                        $json = json_decode($result, true);
                        $result = "multicast_id: " . $json['multicast_id'] . "success: " . $json['success'] . "failure: " . $json['failure'];
                        $notify_succ = $json['success'];
                        $notify_error = $json['failure'];
                        $notification_status = '1';
                        $dataToInsert = array('pn_msg' => $result, 'user_id' => $uAndrData[$j]->email, 'gcm_id' => $uAndrData[$j]->gcm_regid, 'cmpn_id' => $andrd_id, 'pn_date' => date('Y-m-d H:i:s'), 'pn_type' => 'ANDROID', 'notify_succ' => $notify_succ, 'notify_error' => $notify_error, 'notification_status' => $notification_status);

                        $logInsert = DB::table('cron_env_pnstatus')->insert($dataToInsert);

                        //debug($result);echo $uAndrData[$j]->email;
                    }
                }

                if ($type == 2) {
                    // log insert then update flag
                    DB::table('abc_camp_flag')->where('id', $allUserData[$i]->id)->update(array('and_send_flag' => 1));
                }
                /////////////////////return $result;
            }
        }
    }

}


if (!function_exists('prepare_push_noti_normal')) {

// prepare push notification
    function prepare_push_noti_normal($pn_image, $message, $headln_tit, $uid, $appURls) {
        $pushsiteurl = "https://getquote.apeejaybusinesscentre.com/abcapp/app/";
        $pushcpath = '/var/www/websites/getquote.apeejaybusinesscentre.com/abcapp/app/';
        $err = '';
        $tcnt = 0;
        if (isset($message) && $message != '') {

            $murl = '';

            if ($pn_image != '' && file_exists($pushcpath . "upload/pushn/" . $pn_image)) {
                $murl = $pushsiteurl . "upload/pushn/" . $pn_image;
            }
            $pushMessage = $message;
//ANDROID	
            $uData = DB::table('gcm_users')
                            ->select('gcm_regid')
                            ->join('abc_admin', 'abc_admin.mobile', '=', 'gcm_users.email')
                            ->where('gcm_users.email', $uid)
                            ->get()->toArray();
            if (count($uData) > 0) {
                for ($i = 0; $i < count($uData); $i++) {
                    if ($uData[$i]->gcm_regid != '') {
                        $mTitle = 'ABC';
                        $regIds = array($uData[$i]->gcm_regid);
                        if (isset($headln_tit) && $headln_tit != '')
                            $mTitle = $headln_tit;
                        $AppNavg = '';
                        if (isset($appURls))
                            $AppNavg = $appURls;
                        if ($murl != '') {
                            $message = array('message' => $pushMessage, 'title' => $mTitle, 'style' => 'picture', 'picture' => $murl, 'summaryText' => $pushMessage, 'sound' => 'notify', 'appurl' => $AppNavg);
                        } else {
                            $message = array('message' => $pushMessage, 'title' => $mTitle, 'sound' => 'notify', 'appurl' => $AppNavg);
                        }
                        $result = send_push_notification($regIds, $message);
                    }
                }
            }
//ANDROID
//ios
            $uData = DB::table('ios_users')
                            ->select('gcm_regid')
                            ->join('abc_admin', 'abc_admin.mobile', '=', 'ios_users.email')
                            ->where('ios_users.email', $uid)
                            ->get()->toArray();
            if (count($uData) > 0) {
                for ($i = 0; $i < count($uData); $i++) {
                    if ($uData[$i]->gcm_regid != '') {
                        $mTitle = 'ABC';
                        ////////////////// $regIds = array($uData[0]->gcm_regid);commented on 090519
                        if (isset($headln_tit) && $headln_tit != '')
                            $mTitle = $headln_tit;
                        $AppNavg = '';
                        if (isset($appURls))
                            $AppNavg = $appURls;
                        $message = array('alert' => $pushMessage, 'badge' => '1', 'sound' => 'notify', 'title' => $mTitle, 'appurl' => $AppNavg);
                        $result = send_ios_push_notification($uData[$i]->gcm_regid, $message);
                    }
//ios
                }
            }
        }
    }

}

if (!function_exists('send_push_notification')) {

    //Sending Push Notification
    function send_push_notification($registatoin_ids, $message) {
        if ($_SERVER['HTTP_HOST'] != 'localhost') {

            // Set POST variables
            $url = 'https://fcm.googleapis.com/fcm/send'; //android.googleapis.com/gcm/send

            $fields = array(
                'registration_ids' => $registatoin_ids,
                'data' => $message,
            );

            $headers = array(
                'Authorization: key=' . $_ENV['GOOGLE_API_KEY'],
                'Content-Type: application/json'
            );
            //print_r($headers);
            // Open connection
            $ch = curl_init();

            // Set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);

            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Disabling SSL Certificate support temporarly
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

            // Execute post
            $result = curl_exec($ch);
            if ($result === FALSE) {
                die('Curl failed: ' . curl_error($ch));
            }

            // Close connection
            curl_close($ch);
            return $result;
        }
    }

}
?>
