<?php

use DB;

class DatabaseLogger {

    public function handle($request, $next) {
        DB::connection()->enableQueryLog();
        return $next($request);
    }

    public function terminate($request, $response) {
        $queries = DB::getQueryLog();
        $id = Auth::check() ? Auth::id() : null;
        collect($queries)->each(function ($query) use ($id) {
            DB::table("abc_db_log")->insert(["user_id" => $id, "query" => $query]);
        });
    }

}
?>