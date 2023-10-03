<?php

namespace IDCI\Bundle\GuzzleBundleKnpUOAuth2Plugin;

use EightPoints\Bundle\GuzzleBundle\PluginInterface;
use IDCI\Bundle\GuzzleBundleKnpUOAuth2Plugin\Middleware\OAuthMiddleware;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class IDCIGuzzleBundleKnpUOAuth2Plugin extends Bundle implements PluginInterface
{
    public function getPluginName() : string
    {
        return 'knpu_oauth2';
    }

    public function addConfiguration(ArrayNodeDefinition $pluginNode) : void
    {
        $pluginNode
            ->canBeEnabled()
            /*
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
                ->booleanNode('retry_limit')->defaultValue(5)->end()
            ->end();
            */
        ;
    }

    public function load(array $configs, ContainerBuilder $container) : void
    {
        return;
    }

    public function loadForClient(array $configuration, ContainerBuilder $container, string $clientName, Definition $handler) : void
    {
        dd('here');
        /*
        if (!$configuration['enabled']) {
            return;
        }

        $middlewareOptions = [
            'client_id' => $configuration['client_id'],
            'client_secret' => $configuration['client_secret'],
        ];

        // Define Client
        $providerDefinitionName = sprintf('guzzle_bundle_oauth2_plugin.provider.%s', $clientName);
        $oauthProviderDefinition = new Definition(Client::class);
        $oauthProviderDefinition->addArgument(['base_uri' => $configuration['base_uri']]);
        $oauthProviderDefinition->setPublic(true);
        $container->setDefinition($oauthClientDefinitionName, $oauthClientDefinition);

        // Define Middleware
        $oAuth2MiddlewareDefinitionName = sprintf('guzzle_bundle_oauth2_plugin.middleware.%s', $clientName);
        $oAuth2MiddlewareDefinition = new Definition(OAuthMiddleware::class);
        $oAuth2MiddlewareDefinition->addArgument(new Reference($oauthClientDefinitionName));
        $oAuth2MiddlewareDefinition->addArgument($middlewareOptions);
        $oAuth2MiddlewareDefinition->setPublic(true);
        $container->setDefinition($oAuth2MiddlewareDefinitionName, $oAuth2MiddlewareDefinition);

        $onBeforeExpression = new Expression(sprintf('service("%s").onBefore()', $oAuth2MiddlewareDefinitionName));
        //$onFailureExpression = new Expression(sprintf('service("%s").onFailure(%d)', $oAuth2MiddlewareDefinitionName, $configuration['retry_limit']));

        $handler->addMethodCall('push', [$onBeforeExpression]);
        //$handler->addMethodCall('push', [$onFailureExpression]);
        */
    }
}