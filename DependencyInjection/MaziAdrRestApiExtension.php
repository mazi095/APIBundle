<?php

declare(strict_types=1);

namespace Mazi\AdrRestApi\DependencyInjection;

use Mazi\AdrRestApi\Action\ActionInterface;
use Mazi\AdrRestApi\EventSubscriber\ApiResponseSubscriber;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MaziAdrRestApiExtension extends Extension
{
    /**
     * @param array            $configs
     * @param ContainerBuilder $container
     *
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        if ($config['subscribers']['api_response_subscriber']) {
            $container->register('Mazi\AdrRestApi\EventSubscriber\ApiResponseSubscriber', ApiResponseSubscriber::class)
                ->addArgument(new Reference('jms_serializer'))
                ->addArgument(new Reference(UrlGeneratorInterface::class))
                ->addTag(
                    'kernel.event_subscriber'
                );
        }

        $container->registerForAutoconfiguration(ActionInterface::class)
            ->addTag('controller.service_arguments')
            ->addTag('route.annotation');

//        $loader = new YamlFileLoader($container, new FileLocator(\dirname(__DIR__).'/config'));
//        $loader->load('services_autoconfigure.yml');
    }
}
