<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileSystem;

use Spryker\Zed\FileSystem\Dependency\Service\FileSystemToFlysystemBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class FileSystemDependencyProvider extends AbstractBundleDependencyProvider
{

    const SERVICE_FLYSYSTEM = 'service flysystem';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addFlysystemService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFlysystemService(Container $container)
    {
        $container[static::SERVICE_FLYSYSTEM] = function (Container $container) {
            return new FileSystemToFlysystemBridge($container->getLocator()->flysystem()->service());
        };

        return $container;
    }

}
