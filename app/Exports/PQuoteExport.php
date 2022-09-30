<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Modal\PQuote;
use Illuminate\Http\Request;
use Auth;
Use DB;

class PQuoteExport implements FromCollection, WithHeadings {

    use Exportable;


    public function __construct(Request $request, PQuote $detail) {

      
    }

    public function collection() {

        $query = DB::table('abc_ms_partnership_opper')
                ->select('po_name','po_email','po_phone','po_loc','po_text','created_at')->orderby('created_at','desc')->get();

        foreach($query as $key=>$value){
            $loc = getLocationName($value->po_loc);
            $value->po_loc =  $loc->loc_name;
        }
        return $query;
    }

    public function headings(): array {
        return [
            "Name", "Email", "Mobile", "Location","Query" ,"Date & Time"
        ];
    }

}

?>