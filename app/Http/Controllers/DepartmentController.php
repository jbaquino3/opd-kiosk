<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class DepartmentController extends Controller
{
    //
    public function loadDepartments() {
        if($tsid = \Request::get('tsid')) {
            $getDepartments = DB::select("SELECT * FROM dbn.opd_tsfilter as tf LEFT JOIN hospital.dbo.htypser as ht ON tf.tscode = ht.tscode WHERE tf.tsid = '".$tsid."' AND ht.tsstat = 'A' ");
            return $getDepartments;
        }
    }

    public function loadHospitals() {
        $getHospitals = DB::select("SELECT * FROM dbo.fhud_hospital WHERE hfstat = 'A' ");
        return $getHospitals;
    }
}
