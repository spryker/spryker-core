<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class FileManagerDependencyProvider extends AbstractBundleDependencyProvider
{
    const SERVICE_FILE_MANAGER = 'SERVICE_FILE_MANAGER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[static::SERVICE_FILE_MANAGER] = function (Container $container) {
            return $container->getLocator()->fileManager()->service();
        };

        return $container;
    }
}
