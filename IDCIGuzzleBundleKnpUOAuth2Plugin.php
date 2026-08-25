<?php

namespace IDCI\Bundle\GuzzleBundleKnpUOAuth2Plugin;

use EightPoints\Bundle\GuzzleBundle\PluginInterface;
use IDCI\Bundle\GuzzleBundleKnpUOAuth2Plugin\Middleware\OAuth2Middleware;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class IDCIGuzzleBundleKnpUOAuth2Plugin extends Bundle implements PluginInterface
{
    public function getPluginName(): string
    {
        return 'knpu_oauth2';
    }

    public function addConfiguration(ArrayNodeDefinition $pluginNode): void
    {
        $pluginNode
            ->canBeEnabled()
            ->validate()
                ->ifTrue(function (array $config) {
                    return true === $config['enabled'] && empty($config['client']);
                })
                ->thenInvalid('client is required')
            ->end()
            ->children()
                ->scalarNode('client')->defaultNull()->end()
                ->booleanNode('persistent')->defaultFalse()->end()
                ->scalarNode('cache_service_id')->defaultValue('cache.app')->end()
                ->scalarNode('cache_key')->defaultValue('access_token')->end()
                ->integerNode('retry_limit')->min(0)->defaultValue(5)->end()
            ->end();
    }

    public function load(array $configs, ContainerBuilder $container): void
    {
    }

    public function loadForClient(array $configuration, ContainerBuilder $container, string $clientName, Definition $handler): void
    {
        if (!$configuration['enabled']) {
            return;
        }

        $knpuClientDefinitionName = sprintf('knpu.oauth2.client.%s', $configuration['client']);

        $oAuth2MiddlewareDefinitionName = sprintf('idci_guzzle_bundle_knpu_oauth2_plugin.middleware.%s', $clientName);
        $oAuth2MiddlewareDefinition = new Definition(OAuth2Middleware::class);
        $oAuth2MiddlewareDefinition->setPublic(true);
        $oAuth2MiddlewareDefinition->addTag('idci_guzzle_bundle_knpu_oauth2_plugin.middleware');
        $oAuth2MiddlewareDefinition->addMethodCall('setCacheKey', [$configuration['cache_key']]);
        $oAuth2MiddlewareDefinition->addMethodCall('setClient', [new Reference($knpuClientDefinitionName)]);

        if ($configuration['persistent']) {
            $oAuth2MiddlewareDefinition->addMethodCall('setCache', [new Reference($configuration['cache_service_id'])]);
        }

        $container->setDefinition($oAuth2MiddlewareDefinitionName, $oAuth2MiddlewareDefinition);

        $onBeforeExpression = new Expression(sprintf('service("%s").onBefore()', $oAuth2MiddlewareDefinitionName));
        $handler->addMethodCall('push', [$onBeforeExpression]);
    }
}
