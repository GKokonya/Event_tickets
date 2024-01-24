<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MpesaReversal extends Model
{
    use HasFactory;

    protected $fillable = [
        'ResultCode',
        'ResultDesc',
        'ResultType',
        'OriginatorConversationID',
        'ConversationID',
        'TransactionID',
        'DebitAccountBalance',
        'Amount',
        'TransCompletedTime',
        'OriginalTransactionID ',
        'Charge',
        'CreditPartyPublicName',
        'DebitPartyPublicName',

    ];



}
