<?php

namespace Bundle\AWeekOfSymfonyBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;

class AWeekOfSymfonyExtension extends Extension
{
    public function configLoad($config, ContainerBuilder $container)
    {
        if (!$container->hasDefinition('awos')) {
            $loader = new YamlFileLoader($container, __DIR__.'/../Resources/config');

            $loader->load('entry.yml');
        }
    }

    public function getXsdValidationBasePath()
    {
        return __DIR__.'/../Resources/config/schema';
    }

    public function getNamespace()
    {
        return 'http://fivestar.fm/schema/dic/aweekofsymfony';
    }

    public function getAlias()
    {
        return 'awos';
    }
}
