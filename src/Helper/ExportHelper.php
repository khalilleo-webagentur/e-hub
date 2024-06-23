<?php

declare(strict_types=1);

namespace App\Helper;

final class ExportHelper
{
    // Exported_FAQ_20230813_143524.json;

    public const DEFAULT_FAQ_FILE_NAME = 'FAQ';
    public const DEFAULT_SCHEDULE_FILE_NAME = 'Schedule_Calls';
    public const DEFAULT_ARCHIVED_SYSTEM_LOGS_FILE_NAME = 'Archived_System_Logs';
    public const DEFAULT_TEAMS_MEMBERS_FILE_NAME = 'Teams_Members_Data';
    public const DEFAULT_REFERENCES_FILE_NAME = 'References';
    public const DEFAULT_TESTIMONIALS_FILE_NAME = 'Testimonials';
    public const DEFAULT_IP_BLACKLIST_FILE_NAME = 'IP_Blacklist';

    // User
    public const DEFAULT_USERS_FILE_NAME = 'Users';

    // Contact Form
    public const DEFAULT_CONTACT_FORM_FILE_NAME = 'Contact_From_Messages';
    public const DEFAULT_ARCHIVED_CONTACT_FORM_FILE_NAME = 'Archived_Contact_From_Messages';

    // Blog
    public const DEFAULT_BLOG_CATEGORIES_FILE_NAME = 'Blog_Categories';
    public const DEFAULT_BLOG_SOCIAL_SHARE_LINKS_FILE_NAME = 'Social_Share_Links';
    public const DEFAULT_BLOG_TAGS_FILE_NAME = 'Blog_Tags';

    // Newsletter
    public const DEFAULT_NEWSLETTER_FILE_NAME = 'Newsletters_Data';
    public const DEFAULT_NEWSLETTER_SUBSCRIBERS_FILE_NAME = 'Newsletter_Subscribers_Data';
    public const DEFAULT_NEWSLETTER_SUBSCRIBERS_EMAILS_FILE_NAME = 'Newsletter_Subscribers_Emails';

    // Datetime Format
    public const DEFAULT_DATETIME_FORMAT = 'Y-m-d H:i:s';
    public const DEFAULT_DATETIME_FORMAT_IN_FILE_NAME = 'Ymd_His';
}