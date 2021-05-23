<?php

declare(strict_types=1);

namespace DatingLibre\CcBillBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class DatingLibreCcBillExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $configuration = $this->processConfiguration(new Configuration(), $configs);
        $container->setParameter('datinglibre.ccbill_username', $configuration['username']);
        $container->setParameter('datinglibre.ccbill_client_account', $configuration['clientAccount']);
        $container->setParameter('datinglibre.ccbill_client_sub_account', $configuration['clientSubAccount']);

        $loader->load('services.yaml');
    }
}
