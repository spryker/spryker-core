<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileSystem;

use Spryker\Service\FileSystem\Dependency\Service\FileSystemToFlysystemBridge;
use Spryker\Service\Kernel\AbstractBundleDependencyProvider;
use Spryker\Service\Kernel\Container;

class FileSystemDependencyProvider extends AbstractBundleDependencyProvider
{

    const SERVICE_FLYSYSTEM = 'service flysystem';

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = parent::provideServiceDependencies($container);

        $container = $this->addFlysystemService($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    protected function addFlysystemService(Container $container)
    {
        $container[static::SERVICE_FLYSYSTEM] = function (Container $container) {
            return new FileSystemToFlysystemBridge($container->getLocator()->flysystem()->service());
        };

        return $container;
    }

}
