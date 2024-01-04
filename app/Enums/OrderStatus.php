<?php

namespace App\Enums;

enum OrderStatus:string {
    case Pending='pending';
    case Paid='paid';
    case Completed='completed';
    case Failed='failed';
    case Refunded='refunded';

}
