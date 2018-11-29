<?php

declare(strict_types=1);

namespace MajidMvulle\Bundle\NotificationBundle\Interfaces;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Interface MailConfigInterface.
 *
 * @author Majid Mvulle <majid@majidmvulle.com>
 */
interface MailConfigInterface
{
    public function getSubject(): string;

    public function getRecipient(): string;

    public function getSender(): string;

    public function getState(): string;

    public function getPriority(): int;

    public function getSendAfter(): \DateTime;

    public function getAttachments(): ArrayCollection;

    public function getCc(): array;

    public function getBcc(): array;

    public function getHeaders(): array;
}
