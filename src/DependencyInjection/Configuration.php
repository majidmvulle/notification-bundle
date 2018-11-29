<?php

declare(strict_types=1);

namespace MajidMvulle\Bundle\NotificationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('majidmvulle_notification')->addDefaultsIfNotSet();

        $this->addMailerSection($rootNode);

        return $treeBuilder;
    }

    private function addMailerSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('mailer')
                    ->children()
                        ->variableNode('entity_class')->end()
                        ->variableNode('entity_manager')->end()
                        ->scalarNode('css_file_path')->end()
                        ->scalarNode('smtp_url')->isRequired()->end()
                        ->scalarNode('smtp_username')->isRequired()->end()
                        ->scalarNode('smtp_password')->isRequired()->end()
                        ->scalarNode('smtp_port')->isRequired()->end()
                        ->scalarNode('smtp_encryption')->end()
                    ->end()
                ->end()
            ->end();
    }
}
