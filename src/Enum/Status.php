<?php declare(strict_types=1);

namespace Shwrm\Tracking\Enum;

class Status
{
    const NEW       = 'new';
    const SENT      = 'sent';
    const RETURNED  = 'returned';
    const DELIVERED = 'delivered';
    const ERROR     = 'error';

    const STATUSES = [
        self::NEW,
        self::SENT,
        self::RETURNED,
        self::DELIVERED,
        self::ERROR,
    ];
}
