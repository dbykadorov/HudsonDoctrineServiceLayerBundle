<?php

/**
 * This file is part of the "Doctrine service layer" bundle.
 *
 * Copyright Dmitry Bykadorov <dmitry.bykadorov@gmail.com>
 *
 */

namespace Hudson\Bundle\DoctrineServiceLayerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * @author Dmitry Bykadorov <dmitry.bykadorov@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('hudson_doctrine_service_layer');

        return $treeBuilder;
    }
}
