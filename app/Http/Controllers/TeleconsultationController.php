<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class TeleconsultationController extends Controller
{
    public function getScheduledConsultations() {
        $scheduled = DB::select("
            select q.*, fullname=hp.patlast + ', ' + hp.patfirst, ht.tsdesc from hospital.dbo.kiosk_teleconsult_queue q
            left join hospital.dbo.hperson hp
            on hp.hpercode = q.hpercode
            left join hospital.dbo.htypser ht
            on ht.tscode = q.tscode
            where q.walkin = 0
            order by q.created_at
        ");

        return $scheduled;
    }

    public function getWalkinConsultations() {
        $scheduled = DB::select("
            select q.*, fullname=hp.patlast + ', ' + hp.patfirst, ht.tsdesc from hospital.dbo.kiosk_teleconsult_queue q
            left join hospital.dbo.hperson hp
            on hp.hpercode = q.hpercode
            left join hospital.dbo.htypser ht
            on ht.tscode = q.tscode
            where q.walkin = 1
            order by q.created_at
        ");

        return $scheduled;
    }
}
