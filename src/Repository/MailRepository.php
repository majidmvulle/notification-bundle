<?php

declare(strict_types=1);

namespace MajidMvulle\Bundle\NotificationBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use MajidMvulle\Bundle\NotificationBundle\Constants;
use MajidMvulle\Bundle\NotificationBundle\Interfaces\MailRepositoryInterface;

/**
 * Class MailRepository.
 *
 * @author Majid Mvulle <majid@majidmvulle.com>
 */
class MailRepository extends DocumentRepository implements MailRepositoryInterface
{
    public function findPending(int $limit = Constants::FETCHING_LIMIT): array
    {
        $pendingMails = $this->createQueryBuilder()
            ->sort('priority', 'ASC')
            ->sort('created_at', 'ASC')
            ->field('state')->equals(Constants::STATE_PENDING)
            ->field('send_after')->lte(new \DateTime())
            ->limit($limit)
            ->hydrate(true)
            ->getQuery()
            ->execute()
        ;

        return $pendingMails->toArray();
    }
}
