<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class QueueController extends Controller
{
    //
    public function generateQueue(Request $request) {
        $hpercode = $request['hpercode'];
        $priority = $request['priority'];
        $tscode = $request['tscode'];
        $opdtype = $request['opd_type'];

        $generateQueue = DB::select("EXEC dbn.sp_kiosk_generateQueue '".$hpercode."', '".$tscode."', '".$priority."', '".$opdtype."' ");
        return $generateQueue;
    }

    public function cancelQueue() {
        if($enccode = \Request::get('enccode')) {
            $cancel_queue = DB::delete("EXEC dbn.sp_kiosk_cancelQueue '".$enccode."' ");
            return 1;
        }
    }

    public function printQueue(Request $request) {
        $enccode = $request['enccode'];
        $pattype = $request['pat_type'];
        $phic = $request['phic'];
        $refhospital = $request['ref_hospital'];
        
        $print_queue = DB::select("EXEC dbn.sp_kiosk_printQueue 
            '".$enccode."',
            '".$pattype."',
            '".$phic."',
            '".$refhospital."'
        ");
        return 1;
        
    }

    public function setTeleconsultation() {
        $enccode = \Request::get('enccode');
        $setTeleco = DB::select("EXEC dbn.sp_kiosk_setTeleconsultation '".$enccode."' ");
        return $setTeleco;
    }

    public function revTeleconsultation() {
        $enccode = \Request::get('enccode');
        $revTeleco = DB::select("EXEC dbn.sp_kiosk_revTeleconsultation '".$enccode."' ");
        return $revTeleco;
    }
}
