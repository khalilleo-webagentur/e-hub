<?php

declare(strict_types=1);

namespace App\Helper;

final class Job
{
    /**
     * Delete inactive subscribers from newsletter that less than last 6 months
     */
    public const DELETE_INACTIVE_SUBSCRIBER_MODIFIER = '-6 months';

}