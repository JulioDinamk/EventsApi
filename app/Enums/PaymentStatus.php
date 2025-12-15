<?php

namespace App\Enums;

enum PaymentStatus: int
{
    case WAITING_FOR_PAYMENT = 1;
    case UNDER_REVIEW = 2;
    case PAID = 3;
    case AVAILABLE = 4;
    case IN_DISPUTE = 5;
    case RETURNED = 6;
    case CANCELED = 7;
    case EXEMPT = 8;

    /**
     * Returns the English label for the payment status.
     */
    public function label(): string
    {
        return match($this) {
            self::WAITING_FOR_PAYMENT => 'Waiting for Payment',
            self::UNDER_REVIEW => 'Under Review',
            self::PAID => 'Paid',
            self::AVAILABLE => 'Available',
            self::IN_DISPUTE => 'In Dispute',
            self::RETURNED => 'Returned',
            self::CANCELED => 'Canceled',
            self::EXEMPT => 'Exempt',
        };
    }
}
