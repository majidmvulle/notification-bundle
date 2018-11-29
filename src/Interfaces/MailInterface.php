<?php

declare(strict_types=1);

namespace MajidMvulle\Bundle\NotificationBundle\Interfaces;

use Doctrine\Common\Collections\ArrayCollection;
use MajidMvulle\Bundle\NotificationBundle\Document\Attachment;

/**
 * Interface MailerInterface.
 *
 * @author Majid Mvulle <majid@majidmvulle.com>
 */
interface MailInterface
{
    public function getId(): string;

    public function getSubject(): string;

    public function setSubject(string $subject): self;

    public function getRecipient(): string;

    public function setRecipient(string $recipient): self;

    public function getSender(): string;

    public function setSender(string $sender): self;

    public function getState(): string;

    public function setState(string $state): self;

    public function getPriority();

    public function setPriority($priority): self;

    public function getSendAfter(): \DateTime;

    public function setSendAfter(\DateTime $sendAfter): self;

    public function getTemplate(): array;

    public function setTemplate(array $template): self;

    public function getCreatedAt(): \DateTime;

    public function setCreatedAt(\DateTime $createdAt): self;

    public function getUpdatedAt(): \DateTime;

    public function setUpdatedAt(\DateTime $updatedAt): self;

    public function getAttachments(): ArrayCollection;

    public function addAttachment(Attachment $attachment): self;

    public function removeAttachment(Attachment $attachment): self;

    public function setAttachments(ArrayCollection $attachments): self;

    public function getException(): string;

    public function setException(string $exception): self;

    public function getCc(): array;

    public function addCc(string $cc): self;

    public function removeCc(string $cc): self;

    public function setCc(array $cc): self;

    public function getBcc(): array;

    public function addBcc(string $bcc): self;

    public function removeBcc(string $bcc): self;

    public function setBcc(array $bcc): self;

    public function getHeaders(): array;

    public function addHeader(string $key, string $value): self;

    public function removeHeader(string $key): self;

    public function setHeaders(array $headers): self;
}
