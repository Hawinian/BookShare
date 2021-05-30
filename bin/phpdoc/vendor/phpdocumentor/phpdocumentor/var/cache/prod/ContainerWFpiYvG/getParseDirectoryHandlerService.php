<?php

namespace ContainerWFpiYvG;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/*
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getParseDirectoryHandlerService extends phpDocumentor_KernelProdContainer
{
    /*
     * Gets the private 'phpDocumentor\Guides\RestructuredText\Handlers\ParseDirectoryHandler' shared autowired service.
     *
     * @return \phpDocumentor\Guides\RestructuredText\Handlers\ParseDirectoryHandler
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 4).'/src/Guides/RestructuredText/Handlers/ParseDirectoryHandler.php';
        include_once \dirname(__DIR__, 4).'/src/Guides/RestructuredText/Builder/Scanner.php';
        include_once \dirname(__DIR__, 4).'/src/Guides/RestructuredText/Builder/ParseQueueProcessor.php';
        include_once \dirname(__DIR__, 4).'/src/Guides/Metas.php';

        return $container->privates['phpDocumentor\\Guides\\RestructuredText\\Handlers\\ParseDirectoryHandler'] = new \phpDocumentor\Guides\RestructuredText\Handlers\ParseDirectoryHandler(new \phpDocumentor\Guides\RestructuredText\Builder\Scanner(($container->privates['phpDocumentor\\Guides\\Metas'] ?? ($container->privates['phpDocumentor\\Guides\\Metas'] = new \phpDocumentor\Guides\Metas()))), new \phpDocumentor\Guides\RestructuredText\Builder\ParseQueueProcessor(($container->services['tactician.commandbus.default'] ?? $container->load('getTactician_Commandbus_DefaultService'))));
    }
}
