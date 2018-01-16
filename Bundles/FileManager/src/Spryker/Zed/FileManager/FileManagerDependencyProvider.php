<?php

namespace Spryker\Zed\FileManager;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class FileManagerDependencyProvider extends AbstractBundleDependencyProvider
{

    const CLIENT_FILE_MANAGER = 'CLIENT_FILE_MANAGER';

    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::CLIENT_FILE_MANAGER] = function (Container $container) {
            return $container->getLocator()->fileManager()->client();
        };

        return $container;
    }

}
