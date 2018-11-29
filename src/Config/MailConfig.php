<?php

declare(strict_types=1);

namespace MajidMvulle\Bundle\NotificationBundle\Config;

use Doctrine\Common\Collections\ArrayCollection;
use MajidMvulle\Bundle\NotificationBundle\Constants;
use MajidMvulle\Bundle\NotificationBundle\Interfaces\MailConfigInterface;

/**
 * Class MailerConfig.
 *
 * @author Majid Mvulle <majid@majidmvulle.com>
 */
class MailConfig implements MailConfigInterface
{
    public $subject;
    public $recipient;
    public $sender;
    public $state = Constants::STATE_PENDING;
    public $priority = Constants::PRIORITY_DEFAULT;
    public $sendAfter;
    public $attachments;
    public $cc = [];
    public $bcc = [];
    public $headers = [];

    public function __construct()
    {
        $this->sendAfter = new \DateTime();
        $this->attachments = new ArrayCollection();
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getRecipient(): string
    {
        return $this->recipient;
    }

    public function getSender(): string
    {
        return $this->sender;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function getSendAfter(): \DateTime
    {
        return $this->sendAfter;
    }

    public function getAttachments(): ArrayCollection
    {
        return $this->attachments;
    }

    public function getCc(): array
    {
        return $this->cc;
    }

    public function getBcc(): array
    {
        return $this->bcc;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }
}
