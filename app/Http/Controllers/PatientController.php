<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class PatientController extends Controller
{
    //

    public function searchPatient(Request $request) {
        $hpercode = $request['hpercode'];
        $lastname = $request['lastname'];
        $firstname = $request['firstname'];
        $midname = $request['midname'];

        $search_patient = DB::select("EXEC dbn.sp_kiosk_searchPatient
            '".$hpercode."',
            '".$lastname."',
            '".$firstname."',
            '".$midname."'
        ");

        if (count($search_patient) > 0) {
            return $search_patient;
        } else {
            return 0;
        }
    }

    public function logPatient() {
        if ($hpercode = \Request::get('hpercode')) {
            $checkEncounter = DB::select(" EXEC dbn.sp_kiosk_checkEncounter '".$hpercode."' ");
            if (count($checkEncounter) > 0) {
                return 1;
            } else {
                $getPatient = DB::select("EXEC dbn.sp_kiosk_getPatientDetails '".$hpercode."' ");
                return $getPatient;
            }
        }
    }

    public function getHistory() {
        if($hpercode = \Request::get('hpercode')) {
            $history = DB::select("EXEC dbn.sp_kiosk_getHistory '".$hpercode."'");
            return $history;
        }
    }

    public function opdBio() {
        if ($hpercode = \Request::get('hpercode')) {
            $getOpdBio = DB::select("EXEC dbn.sp_kiosk_getOpdBioRecord '".$hpercode."' ");
            if (count($getOpdBio) > 0) {
                return 1;
            } else {
                return 0;
            }
        }
    }

    public function getPatientDetails() {
        if ($hpercode = \Request::get('hpercode')) {
            $getPatientDeets = DB::select("EXEC dbn.sp_kiosk_getPatientInfo '".$hpercode."' ");

            return $getPatientDeets;
        }
    }

    public function registerPatient(Request $request){
        $encoded_data = \Request::get('imgdata');
        $hpercode = \Request::get('hpercode');

        
        $folderPath = '//192.168.7.15/uploads/opd_pics/';
        $fileName = $hpercode.'.jpeg';
        $file = $folderPath.$fileName;

        $encoded_data = str_replace('data:image/jpeg;base64,', '', $encoded_data);
        $encoded_data = str_replace(' ', '+', $encoded_data);
        $data = base64_decode($encoded_data);
        
        file_put_contents($file, $data);
        

        $create_opdbio = DB::insert("EXEC dbn.sp_kiosk_createBioRecord '".$hpercode."', '".$fileName."' ");
        return 1;
        
    }

    public function registerPatientNoimg(Request $request){
        $hpercode = \Request::get('hpercode');
        $create_opdbio = DB::insert("EXEC dbn.sp_kiosk_createBioRecordnoImg '".$hpercode."' ");
        return 1;
    }

    public function getRecentLogs() {
        $recent_logs = DB::select
        ("EXEC dbn.sp_opd_getRecentLogs");
    
        return $recent_logs;
    }

    public function getRecentLogsPcc(Request $request) {
        $opdtype = $request['opdtype'];
        $recent_logs = DB::select("EXEC dbn.sp_kiosk_getRecentLogs '".$opdtype."' ");
    
        return $recent_logs;
    }

    public function countPatients() {
        $countPatient = DB::select("SELECT count(*) as 'countPatients', getdate() as 'dateNow' from dbo.hopdlog where cast(opddate as date) = cast(getdate() as date) and tscode != 'HEMOD' and tscode != 'MALCN'");
        return $countPatient;
    }
}
