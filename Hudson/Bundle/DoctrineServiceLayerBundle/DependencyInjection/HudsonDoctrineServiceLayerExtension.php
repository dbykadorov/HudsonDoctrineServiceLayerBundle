<?php

/**
 * This file is part of the "Doctrine service layer" bundle.
 *
 * Copyright Dmitry Bykadorov <dmitry.bykadorov@gmail.com>
 *
 */

namespace Hudson\Bundle\DoctrineServiceLayerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * @author Dmitry Bykadorov <dmitry.bykadorov@gmail.com>
 */
class HudsonDoctrineServiceLayerExtension extends Extension
{
    /**
     * Loads a specific configuration.
     *
     * @param array $configs
     * @param ContainerBuilder $container A ContainerBuilder instance
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
    }
}
