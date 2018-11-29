<?php

declare(strict_types=1);

namespace MajidMvulle\Bundle\NotificationBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class MajidMvulleNotificationExtension extends ConfigurableExtension
{
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container)
    {
        $container->setParameter('majidmvulle.notification.mailer.css_file_path', $mergedConfig['mailer']['css_file_path']);
        $container->setParameter('majidmvulle.notification.mailer.entity_class', $mergedConfig['mailer']['entity_class']);
        $container->setParameter('majidmvulle.notification.mailer.entity_manager', $mergedConfig['mailer']['entity_manager']);
        $container->setParameter('majidmvulle.notification.mailer.smtp_url', $mergedConfig['mailer']['smtp_url']);
        $container->setParameter('majidmvulle.notification.mailer.smtp_username', $mergedConfig['mailer']['smtp_username']);
        $container->setParameter('majidmvulle.notification.mailer.smtp_password', $mergedConfig['mailer']['smtp_password']);
        $container->setParameter('majidmvulle.notification.mailer.smtp_encryption', $mergedConfig['mailer']['smtp_encryption']);
        $container->setParameter('majidmvulle.notification.mailer.smtp_port', $mergedConfig['mailer']['smtp_port']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');
    }

    public function getAlias()
    {
        return 'majidmvulle_notification';
    }
}
