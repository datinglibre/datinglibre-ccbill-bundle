<?php

declare(strict_types=1);

namespace DatingLibre\CcBillBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('dating_libre_ccbill');

        $treeBuilder->getRootNode()
            ->children()
            ->scalarNode('username')->end()
            ->scalarNode('clientAccount')->end()
            ->scalarNode('clientSubAccount')->end()
            ->end();

        return $treeBuilder;
    }
}
