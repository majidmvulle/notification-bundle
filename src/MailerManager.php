<?php

declare(strict_types=1);

namespace MajidMvulle\Bundle\NotificationBundle;

use Doctrine\ORM\EntityManagerInterface;
use MajidMvulle\Bundle\NotificationBundle\Interfaces\MailConfigInterface;
use MajidMvulle\Bundle\NotificationBundle\Interfaces\MailInterface;
use MajidMvulle\Bundle\NotificationBundle\Interfaces\MailTemplateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class MailerManager.
 *
 * @author Majid Mvulle <majid@majidmvulle.com>
 */
class MailerManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var string
     */
    private $entityClass;

    public function __construct(ContainerInterface $container)
    {
        $this->entityManager = $container->get($container->getParameter('majidmvulle.notification.mailer.entity_manager'));
        $this->entityClass = $container->getParameter('majidmvulle.notification.mailer.entity_class');
    }

    public function send(MailConfigInterface $mailConfig, MailTemplateInterface $mailerTemplate): void
    {
        $template = [
            'path' => $mailerTemplate->getTemplatePath(),
            'parameters' => $mailerTemplate->getParameters(),
            'is_html' => $mailerTemplate->isHtml(),
        ];

        /** @var MailInterface $mail */
        $mail = new $this->entityClass();
        $mail->setSubject($mailConfig->getSubject());
        $mail->setRecipient($mailConfig->getRecipient());
        $mail->setSender($mailConfig->getSender());
        $mail->setState($mailConfig->getState());
        $mail->setPriority($mailConfig->getPriority());
        $mail->setSendAfter($mailConfig->getSendAfter());
        $mail->setTemplate($template);
        $mail->setAttachments($mailConfig->getAttachments());
        $mail->setCc($mailConfig->getCc());
        $mail->setBcc($mailConfig->getBcc());
        $mail->setHeaders($mailConfig->getHeaders());

        // @todo: Add validation for this entity according to annotations
        $this->entityManager->persist($mail);
        $this->entityManager->flush();
    }
}
