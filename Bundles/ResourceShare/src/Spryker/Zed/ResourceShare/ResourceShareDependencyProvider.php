<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ResourceShare;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ResourceShare\Dependency\Facade\ResourceShareToUuidFacadeBridge;

class ResourceShareDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_UUID = 'FACADE_UUID';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addUuidFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUuidFacade(Container $container): Container
    {
        $container[static::FACADE_UUID] = function (Container $container) {
            return new ResourceShareToUuidFacadeBridge(
                $container->getLocator()->uuid()->facade()
            );
        };

        return $container;
    }
}
