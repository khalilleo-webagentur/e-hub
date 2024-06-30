<?php

declare(strict_types=1);

namespace App\Helper;

final class ExportHelper
{
    // User
    public const DEFAULT_USERS_FILE_NAME = 'Users';

    // Newsletter
    public const DEFAULT_NEWSLETTER_FILE_NAME = 'Newsletters_Data';
    public const DEFAULT_NEWSLETTER_SUBSCRIBERS_FILE_NAME = 'Newsletter_Subscribers_Data';
    public const DEFAULT_NEWSLETTER_SUBSCRIBERS_EMAILS_FILE_NAME = 'Newsletter_Subscribers_Emails';

    // Datetime Format
    public const DEFAULT_DATETIME_FORMAT = 'Y-m-d H:i:s';
    public const DEFAULT_DATETIME_FORMAT_IN_FILE_NAME = 'Ymd_His';
}