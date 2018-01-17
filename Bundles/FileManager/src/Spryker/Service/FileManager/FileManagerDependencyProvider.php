<?php

namespace Spryker\Service\FileManager;


use Spryker\Service\FileManager\Dependency\Plugin\FileManagerPluginInterface;
use Spryker\Service\Kernel\AbstractBundleDependencyProvider;
use Spryker\Service\Kernel\Container;

class FileManagerDependencyProvider extends AbstractBundleDependencyProvider
{

    const FILE_MANAGER_PLUGIN = 'FILE_MANAGER_PLUGIN';

    /**
     * @param Container $container
     * @return Container
     */
    public function provideServiceDependencies(Container $container)
    {
        $container = parent::provideServiceDependencies($container);

        $container[static::FILE_MANAGER_PLUGIN] = function (Container $container) {
            return $this->createFileManagerPlugin();
        };

        return $container;
    }

    /**
     * @return FileManagerPluginInterface
     */
    protected function createFileManagerPlugin()
    {
    }

}
