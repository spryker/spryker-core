<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileManager;

use Spryker\Service\Kernel\AbstractBundleDependencyProvider;
use Spryker\Service\Kernel\Container;

class FileManagerDependencyProvider extends AbstractBundleDependencyProvider
{
    const FILE_MANAGER_PLUGIN = 'FILE_MANAGER_PLUGIN';

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
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
     * @return \Spryker\Service\FileManager\Dependency\Plugin\FileManagerPluginInterface
     */
    protected function createFileManagerPlugin()
    {
    }
}
