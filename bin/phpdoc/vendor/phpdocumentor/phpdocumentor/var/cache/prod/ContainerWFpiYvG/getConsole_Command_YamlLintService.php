<?php

namespace ContainerWFpiYvG;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/*
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getConsole_Command_YamlLintService extends phpDocumentor_KernelProdContainer
{
    /*
     * Gets the private 'console.command.yaml_lint' shared service.
     *
     * @return \Symfony\Bundle\FrameworkBundle\Command\YamlLintCommand
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 6).'/symfony/console/Command/Command.php';
        include_once \dirname(__DIR__, 6).'/symfony/yaml/Command/LintCommand.php';
        include_once \dirname(__DIR__, 6).'/symfony/framework-bundle/Command/YamlLintCommand.php';

        $container->privates['console.command.yaml_lint'] = $instance = new \Symfony\Bundle\FrameworkBundle\Command\YamlLintCommand();

        $instance->setName('lint:yaml');

        return $instance;
    }
}