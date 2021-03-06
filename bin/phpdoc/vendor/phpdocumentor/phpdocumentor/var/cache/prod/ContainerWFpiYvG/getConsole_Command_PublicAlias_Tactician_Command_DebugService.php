<?php

namespace ContainerWFpiYvG;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/*
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getConsole_Command_PublicAlias_Tactician_Command_DebugService extends phpDocumentor_KernelProdContainer
{
    /*
     * Gets the public 'console.command.public_alias.tactician.command.debug' shared service.
     *
     * @return \League\Tactician\Bundle\Command\DebugCommand
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/symfony/console/Command/Command.php';
        include_once \dirname(__DIR__, 6).'/league/tactician-bundle/src/Command/DebugCommand.php';

        return $container->services['console.command.public_alias.tactician.command.debug'] = new \League\Tactician\Bundle\Command\DebugCommand(['default' => ['phpDocumentor\\Guides\\LoadCacheCommand' => 'phpDocumentor\\Guides\\Handlers\\LoadCacheHandler', 'phpDocumentor\\Guides\\PersistCacheCommand' => 'phpDocumentor\\Guides\\Handlers\\PersistCacheHandler', 'phpDocumentor\\Guides\\RenderCommand' => 'phpDocumentor\\Guides\\Handlers\\RenderHandler', 'phpDocumentor\\Guides\\RestructuredText\\ParseDirectoryCommand' => 'phpDocumentor\\Guides\\RestructuredText\\Handlers\\ParseDirectoryHandler', 'phpDocumentor\\Guides\\RestructuredText\\ParseFileCommand' => 'phpDocumentor\\Guides\\RestructuredText\\Handlers\\ParseFileHandler']]);
    }
}
