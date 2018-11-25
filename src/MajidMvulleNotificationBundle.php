<?php

declare(strict_types=1);

namespace MajidMvulle\Bundle\NotificationBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class MajidMvulleNotificationBundle.
 *
 * @author Majid Mvulle <majid@majidmvulle.com>
 */
class MajidMvulleNotificationBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        $this->extension = $this->createContainerExtension();

        return parent::getContainerExtension();
    }
}
