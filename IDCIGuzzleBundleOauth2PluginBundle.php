<?php

namespace IDCI\Bundle\GuzzleBundleOAuth2PluginBundle;

use EightPoints\Bundle\GuzzleBundle\PluginInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class IDCIGuzzleBundleOAuth2PluginBundle extends Bundle implements PluginInterface
{
    public function getPluginName() : string
    {
        return 'oauth2';
    }

    public function addConfiguration(ArrayNodeDefinition $pluginNode) : void
    {
        $pluginNode
            ->canBeEnabled()
            ->validate()
                ->ifTrue(function (array $config) {
                    return $config['enabled'] === true && empty($config['base_uri']);
                })
                ->thenInvalid('base_uri is required')
            ->end()
            ->validate()
                ->ifTrue(function (array $config) {
                    return $config['enabled'] === true && empty($config['client_id']);
                })
                ->thenInvalid('client_id is required')
            ->end()
            ->validate()
                ->ifTrue(function (array $config) {
                    return $config['enabled'] === true && empty($config['client_secret']);
                })
                ->thenInvalid('client_secret is required')
            ->end()
            ->children()
                ->scalarNode('base_uri')->defaultNull()->end()
                ->scalarNode('client_id')->defaultNull()->end()
                ->scalarNode('client_secret')->defaultNull()->end()
                ->scalarNode('token_url')->defaultNull()->end()
                ->scalarNode('scope')->defaultNull()->end()
                ->scalarNode('auth_location')
                    ->defaultValue('headers')
                    ->validate()
                        ->ifNotInArray(['headers', 'body'])
                        ->thenInvalid('Invalid auth_location %s. Allowed values: headers, body.')
                    ->end()
                ->end()
                //->booleanNode('persistent')->defaultFalse()->end()
                //->booleanNode('retry_limit')->defaultValue(5)->end()
            ->end();
        ;
    }

    public function load(array $configs, ContainerBuilder $container) : void
    {
        $extension = new GuzzleBundleOAuth2Extension();
        $extension->load($configs, $container);
    }

    public function loadForClient(array $config, ContainerBuilder $container, string $clientName, Definition $handler) : void
    {
        if ($config['enabled']) {
        }
    }
}