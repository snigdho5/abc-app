<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Modal\ReferClient;
use Illuminate\Http\Request;
use Auth;
Use DB;

class RClientExport implements FromCollection, WithHeadings {

    use Exportable;


    public function __construct(Request $request, ReferClient $detail) {

      
    }

    public function collection() {

        $query = DB::table('abc_ms_refer_client')
                ->select('rc_name','rc_email','rc_mobile','rc_refby','created_at')->orderby('created_at','desc')->get();

        foreach($query as $key=>$value){
            $value->rc_refby =  getCustName1($value->rc_refby);
        }
        return $query;
    }

    public function headings(): array {
        return [
            "Name", "Email", "Mobile", "Refered By","Date & Time"
        ];
    }

}

?>