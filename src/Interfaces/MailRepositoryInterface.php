<?php

declare(strict_types=1);

namespace MajidMvulle\Bundle\NotificationBundle\Interfaces;

use MajidMvulle\Bundle\NotificationBundle\Constants;

/**
 * interface  MailRepositoryInterface.
 *
 * @author Majid Mvulle <majid@majidmvulle.com>
 */
interface MailRepositoryInterface
{
    public function findPending(int $limit = Constants::FETCHING_LIMIT): array;
}
