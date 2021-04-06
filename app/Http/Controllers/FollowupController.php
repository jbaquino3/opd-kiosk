<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;


class FollowupController extends Controller
{
    //
    public function getFollowupPatients() {
        $followupPatients = DB::select("EXEC dbn.sp_kiosk_getFollowUpPatients");
        // return 0;
        return $followupPatients;
    }

    public function logFollowup() {
        $hpercode = \Request::get('hpercode');
        $tscode = \Request::get('tscode');

        $logPatient = DB::insert("EXEC dbn.sp_kiosk_logFollowupPatient '".$hpercode."', '".$tscode."' ");

        return $tscode;
    }

    public function checkEncounter() {
        $hpercode = \Request::get('hpercode');
        $checkEncounter = DB::select("SELECT * FROM dbo.hopdlog WHERE hpercode = '".$hpercode."' AND cast(getdate() as date) = cast(opddate as date) ");
        if (count($checkEncounter) == 0) {
            return 0;
        } else {
            return 1;
        }
    }

    public function getFollowupPatients_bydate(Request $r) {
        $date = $r->date;
        $dept = $r->dept;
        $byDate = DB::select("exec dbn.sp_kiosk_getFollowUpPatients_bydate '$date',  '$dept' ");
        $departments = DB::table("dbo.htypser")
                    ->where('tsstat', 'A')
                    ->orderBy('tsdesc', 'ASC')
                    ->get();

        // return 0;
        return json_encode([
            'all_dept' => $departments,
            'byDate' => $byDate
        ]);
    }

    public function getFollowupPatients_all(Request $r) {
        $date = $r->date;

        $byDate = DB::select("exec dbn.sp_kiosk_getFollowUpPatients_all '$date' ");

        $departments = DB::table("dbo.htypser")
                    ->where('tsstat', 'A')
                    ->orderBy('tsdesc', 'ASC')
                    ->get();

        // return 0;
        return json_encode([
            'all_dept' => $departments,
            'byDate' => $byDate
        ]);
    }

}
