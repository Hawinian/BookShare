<?php

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.

if (\class_exists(\ContainerWFpiYvG\phpDocumentor_KernelProdContainer::class, false)) {
    // no-op
} elseif (!include __DIR__.'/ContainerWFpiYvG/phpDocumentor_KernelProdContainer.php') {
    touch(__DIR__.'/ContainerWFpiYvG.legacy');

    return;
}

if (!\class_exists(phpDocumentor_KernelProdContainer::class, false)) {
    \class_alias(\ContainerWFpiYvG\phpDocumentor_KernelProdContainer::class, phpDocumentor_KernelProdContainer::class, false);
}

return new \ContainerWFpiYvG\phpDocumentor_KernelProdContainer([
    'container.build_hash' => 'WFpiYvG',
    'container.build_id' => '364c4678',
    'container.build_time' => 1622386946,
], __DIR__.\DIRECTORY_SEPARATOR.'ContainerWFpiYvG');
