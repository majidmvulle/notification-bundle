<?php

declare(strict_types=1);

namespace MajidMvulle\Bundle\NotificationBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Attachment.
 *
 * @MongoDB\EmbeddedDocument()
 *
 * @author Majid Mvulle <majid@majidmvulle.com>
 */
class Attachment
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
     */
    private $name;

    /**
     * @MongoDB\File(nullable=false)
     */
    private $file;

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
     * @var int
     *
     * @MongoDB\NotSaved(type="int")
     */
    private $length;

    /**
     * @var \DateTime
     *
     * @MongoDB\NotSaved(type="date")
     */
    private $uploadDate;

    /**
     * @var int
     *
     * @MongoDB\NotSaved(type="int")
     */
    private $chunkSize;

    /**
     * @var string
     *
     * @MongoDB\NotSaved(type="string")
     */
    private $md5;

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getLength(): ?int
    {
        return $this->length;
    }

    public function setLength(int $length): self
    {
        $this->length = $length;

        return $this;
    }

    public function getUploadDate(): ?\DateTime
    {
        return $this->uploadDate;
    }

    public function setUploadDate(\DateTime $uploadDate): self
    {
        $this->uploadDate = $uploadDate;

        return $this;
    }

    public function getChunkSize(): ?int
    {
        return $this->chunkSize;
    }

    public function setChunkSize(int $chunkSize): self
    {
        $this->chunkSize = $chunkSize;

        return $this;
    }

    public function getMd5(): ?string
    {
        return $this->md5;
    }

    public function setMd5(string $md5): self
    {
        $this->md5 = $md5;

        return $this;
    }
}
