<?php

namespace ContainerWFpiYvG;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/*
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getConsole_Command_CachePoolPruneService extends phpDocumentor_KernelProdContainer
{
    /*
     * Gets the private 'console.command.cache_pool_prune' shared service.
     *
     * @return \Symfony\Bundle\FrameworkBundle\Command\CachePoolPruneCommand
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/symfony/console/Command/Command.php';
        include_once \dirname(__DIR__, 6).'/symfony/framework-bundle/Command/CachePoolPruneCommand.php';

        $container->privates['console.command.cache_pool_prune'] = $instance = new \Symfony\Bundle\FrameworkBundle\Command\CachePoolPruneCommand(new RewindableGenerator(function () use ($container) {
            yield 'cache.app' => ($container->services['cache.app'] ?? $container->load('getCache_AppService'));
            yield 'cache.system' => ($container->services['cache.system'] ?? $container->load('getCache_SystemService'));
            yield 'files' => ($container->privates['files'] ?? ($container->privates['files'] = new \phpDocumentor\Parser\Cache\FilesystemAdapter('cG+PzEYWID')));
            yield 'descriptors' => ($container->privates['descriptors'] ?? ($container->privates['descriptors'] = new \phpDocumentor\Parser\Cache\FilesystemAdapter('R+H+nYYIvx')));
        }, 4));

        $instance->setName('cache:pool:prune');

        return $instance;
    }
}
