<?php

namespace App\Enums;

enum MpesaStkPaymentStatus:string {
    case Requested='requested';
    case Success='success';
    case Failed='failed';

}