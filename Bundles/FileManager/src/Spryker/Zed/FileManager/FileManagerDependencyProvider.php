<?php

namespace Spryker\Zed\FileManager;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class FileManagerDependencyProvider extends AbstractBundleDependencyProvider
{

    const SERVICE_FILE_MANAGER = 'SERVICE_FILE_MANAGER';

    /**
     * @param Container $container
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::SERVICE_FILE_MANAGER] = function (Container $container) {
            return $container->getLocator()->fileManager()->service();
        };

        return $container;
    }

}
