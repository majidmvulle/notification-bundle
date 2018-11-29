<?php

declare(strict_types=1);

namespace MajidMvulle\Bundle\NotificationBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Gedmo\Mapping\Annotation as Gedmo;
use MajidMvulle\Bundle\NotificationBundle\Constants;
use MajidMvulle\Bundle\NotificationBundle\Interfaces\MailInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Mailer.
 *
 * @MongoDB\Document(
 *     collection="majidmvulle_notification_mailer",
 *     repositoryClass="MajidMvulle\Bundle\NotificationBundle\Repository\MailRepository"
 * )
 *
 * @author Majid Mvulle <majid@majidmvulle.com>
 */
class Mail implements MailInterface
{
    /**
     * @var string
     *
     * @MongoDB\Id
     */
    private $id;

    /**
     * @var string
     *
     * @MongoDB\Field(type="string", nullable=false)
     *
     * @Assert\NotBlank()
     */
    private $subject;

    /**
     * @var string
     *
     * @MongoDB\Field(type="string", nullable=false)
     *
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $recipient;

    /**
     * @var string
     *
     * @MongoDB\Field(type="string", nullable=false)
     *
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $sender;

    /**
     * @var string
     *
     * @MongoDB\Field(type="string", nullable=false)
     *
     * @Assert\NotBlank()
     */
    private $state;

    /**
     * @MongoDB\Field(type="int", nullable=false)
     *
     * @Assert\NotBlank()
     */
    private $priority;

    /**
     * @var \DateTime
     *
     * @MongoDB\Field(type="date", name="send_after", nullable=false)
     *
     * @Assert\NotBlank()
     */
    private $sendAfter;

    /**
     * Serialized template class.
     *
     * @var array
     *
     * @MongoDB\Field(type="hash")
     */
    private $template;

    /**
     * @var \DateTime
     *
     * @MongoDB\Field(type="date", name="created_at", nullable=false)
     *
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @MongoDB\Field(type="date", name="updated_at", nullable=false)
     *
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

    /**
     * @var ArrayCollection
     *
     * @MongoDB\EmbedMany(targetDocument="MajidMvulle\Bundle\NotificationBundle\Document\Attachment")
     */
    private $attachments;

    /**
     * Serialized exception class.
     *
     * @var string
     *
     * @MongoDB\Field(type="raw", nullable=true)
     */
    private $exception;

    /**
     * @var array
     *
     * @MongoDB\Field(type="hash", nullable=true)
     */
    private $cc = [];

    /**
     * @var array
     *
     * @MongoDB\Field(type="hash", nullable=true)
     */
    private $bcc = [];

    /**
     * @var array
     *
     * @MongoDB\Field(type="hash", nullable=true)
     */
    private $headers = [];

    public function __construct()
    {
        $this->state = Constants::STATE_NEW;
        $this->priority = Constants::PRIORITY_DEFAULT;
        $this->sendAfter = new \DateTime();
        $this->attachments = new ArrayCollection();
    }

    public function __toString()
    {
        return sprintf('Mail(id = %s)', $this->id);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): MailInterface
    {
        $this->subject = $subject;

        return $this;
    }

    public function getRecipient(): string
    {
        return $this->recipient;
    }

    public function setRecipient(string $recipient): MailInterface
    {
        $this->recipient = $recipient;

        return $this;
    }

    public function getSender(): string
    {
        return $this->sender;
    }

    public function setSender(string $sender): MailInterface
    {
        $this->sender = $sender;

        return $this;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): MailInterface
    {
        $this->state = $state;

        return $this;
    }

    public function getPriority()
    {
        return $this->priority;
    }

    public function setPriority($priority): MailInterface
    {
        $this->priority = $priority;

        return $this;
    }

    public function getSendAfter(): \DateTime
    {
        return $this->sendAfter;
    }

    public function setSendAfter(\DateTime $sendAfter): MailInterface
    {
        $this->sendAfter = $sendAfter;

        return $this;
    }

    public function getTemplate(): array
    {
        return $this->template;
    }

    public function setTemplate(array $template): MailInterface
    {
        $this->template = $template;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): MailInterface
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): MailInterface
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getAttachments(): ArrayCollection
    {
        return $this->attachments;
    }

    public function addAttachment(Attachment $attachment): MailInterface
    {
        $this->attachments->add($attachment);

        return $this;
    }

    public function removeAttachment(Attachment $attachment): MailInterface
    {
        $this->attachments->removeElement($attachment);

        return $this;
    }

    public function setAttachments(ArrayCollection $attachments): MailInterface
    {
        $this->attachments = $attachments;

        return $this;
    }

    public function getException(): string
    {
        return $this->exception;
    }

    public function setException(string $exception): MailInterface
    {
        $this->exception = $exception;

        return $this;
    }

    public function getCc(): array
    {
        return $this->cc;
    }

    public function addCc(string $cc): MailInterface
    {
        $this->cc[] = $cc;
        $this->cc = array_unique($this->cc);
        $this->cc = array_values($this->cc);

        return $this;
    }

    public function removeCc(string $cc): MailInterface
    {
        $key = array_search($cc, $this->cc, true);

        if (false !== $key) {
            unset($this->cc[$key]);
            $this->cc = array_values($this->cc);
        }

        return $this;
    }

    public function setCc(array $cc): MailInterface
    {
        $this->cc = $cc;

        return $this;
    }

    public function getBcc(): array
    {
        return $this->bcc;
    }

    public function addBcc(string $bcc): MailInterface
    {
        $this->bcc[] = $bcc;
        $this->bcc = array_unique($this->bcc);
        $this->bcc = array_values($this->bcc);

        return $this;
    }

    public function removeBcc(string $bcc): MailInterface
    {
        $key = array_search($bcc, $this->bcc, true);

        if (false !== $key) {
            unset($this->bcc[$key]);
            $this->bcc = array_values($this->bcc);
        }

        return $this;
    }

    public function setBcc(array $bcc): MailInterface
    {
        $this->bcc = $bcc;

        return $this;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function addHeader(string $key, string $value): MailInterface
    {
        $this->headers[$key] = $value;
        $this->headers = array_unique($this->headers, SORT_REGULAR);

        return $this;
    }

    public function removeHeader(string $key): MailInterface
    {
        if (isset($this->headers[$key])) {
            unset($this->headers[$key]);
        }

        return $this;
    }

    public function setHeaders(array $headers): MailInterface
    {
        $this->headers = $headers;

        return $this;
    }
}
