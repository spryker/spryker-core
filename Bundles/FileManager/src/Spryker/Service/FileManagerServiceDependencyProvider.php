<?php

namespace Spryker\Service\FileManager;


use Spryker\Service\FileManager\Dependency\Service\FileManagerToFileSystemBridge;
use Spryker\Service\Kernel\AbstractBundleDependencyProvider;
use Spryker\Service\Kernel\Container;

class FileManagerServiceDependencyProvider extends AbstractBundleDependencyProvider
{

    const FILE_SYSTEM_SERVICE = 'FILE_SYSTEM_SERVICE';

    /**
     * @param Container $container
     * @return Container
     */
    public function provideServiceDependencies(Container $container)
    {
        $container = parent::provideServiceDependencies($container);

        $container[static::FILE_SYSTEM_SERVICE] = function ($container) {
            $fileSystemService = $container->getLocator()->fileSystem()->service();
            return new FileManagerToFileSystemBridge($fileSystemService);
        };

        return $container;
    }


}