<?php

namespace IDCI\Bundle\GuzzleBundleKnpUOAuth2Plugin\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class InjectMiddlewareKnpUOAuthClientCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        foreach ($container->findTaggedServiceIds('idci_guzzle_bundle_knpu_oauth2_plugin.middleware') as $id => $tags) {
            $middlewareDefinition = $container->findDefinition($id);

            $knpuOAuth2ClientDefinitionName = $middlewareDefinition->getProperties()['knpu_oauth2_client'];

            if (!$container->hasDefinition($knpuOAuth2ClientDefinitionName)) {
                throw new \Exception(sprintf('The given client "%s" is not well configured in "knpu_oauth2_client.clients".', $knpuOAuth2ClientDefinitionName));
            }

            $middlewareDefinition->addMethodCall('setClient', [new Reference($knpuOAuth2ClientDefinitionName)]);
        }
    }
}