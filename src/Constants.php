<?php

namespace MajidMvulle\Bundle\NotificationBundle;
/**
 * Class Constants.
 *
 * @author Majid Mvulle <majid@majidmvulle.com>
 */
final class Constants
{
    const FETCHING_LIMIT = 10;

    /** State if message is inserted, but not yet ready to be sent. */
    const STATE_NEW = 'new';

    /** State if message is inserted, and waiting to be sent. */
    const STATE_PENDING = 'pending';

    /** State if message was never sent, and will never be sent. */
    const STATE_CANCELED = 'canceled';

    /** State if message was sent and has not exited, yet. */
    const STATE_SENDING = 'sending';

    /** State if message exits with a non-successful exit code. */
    const STATE_FAILED = 'failed';

    /** State if message exists with a successful exit code. */
    const STATE_SENT = 'sent';

    const PRIORITY_LOW = -5;
    const PRIORITY_DEFAULT = 0;
    const PRIORITY_HIGH = 5;
}
