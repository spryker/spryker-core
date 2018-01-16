<?php


namespace Spryker\Client\FileManager;


use Spryker\Client\FileManager\Model\Adapter\AdapterInterface;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class FileManagerDependencyProvider extends AbstractDependencyProvider
{
    
    const FILE_MANAGER_ADAPTER = 'FILE_MANAGER_ADAPTER';

    public function provideServiceLayerDependencies(Container $container)
    {
        $container = parent::provideServiceLayerDependencies($container);

        $container[static::FILE_MANAGER_ADAPTER] = function (Container $container) {
            return $this->createFileManagerAdapter($container);
        };

        return $container;
    }

    /**
     * @return AdapterInterface
     */
    public function createFileManagerAdapter()
    {
    }

}
