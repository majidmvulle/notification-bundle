<?php

declare(strict_types=1);

namespace MajidMvulle\Bundle\NotificationBundle\Interfaces;

/**
 * Interface MailTemplateInterface.
 *
 * @author Majid Mvulle <majid@majidmvulle.com>
 */
interface MailTemplateInterface
{
    public function getTemplatePath(): string;

    public function getParameters(): array;

    public function isHtml(): bool;
}
