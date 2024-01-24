<?php

namespace App\Enums;

enum MpesaReversalStatus:string {
    case Requested='requested';
    case Sucess='success';
    case Failed='failed';

}