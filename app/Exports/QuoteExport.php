<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Modal\Query;
use Illuminate\Http\Request;
use Auth;
Use DB;

class QuoteExport implements FromCollection, WithHeadings {

    use Exportable;


    public function __construct(Request $request, Query $detail) {

      
    }

    public function collection() {

        $query = DB::table('abc_ms_query')
                ->select('q_name','q_email','q_phone','q_text','q_service','q_centre','created_at')->where('q_service','!=' ,0)->orderby('created_at','desc')->get();

        foreach($query as $key=>$value){
            $value->q_service =  getSupportServiceNameById($value->q_service);
            $value->q_centre =  getCentreName($value->q_centre);
        }
        return $query;
    }

    public function headings(): array {
        return [
            "Name", "Email", "Mobile", "Message","Support Service" ,"Centre","Date & Time"
        ];
    }

}

?>