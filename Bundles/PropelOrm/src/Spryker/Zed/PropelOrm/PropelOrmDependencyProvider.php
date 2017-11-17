<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelOrm;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\PropelOrm\Dependency\Facade\PropelOrmToLogBridge;

class PropelOrmDependencyProvider extends AbstractBundleDependencyProvider
{
    const FACADE_LOG = 'FACADE_LOG';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addLogFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return array|\Spryker\Zed\Kernel\Container
     */
    protected function addLogFacade(Container $container)
    {
        $container[] = function () use ($container) {
            return new PropelOrmToLogBridge($container->getLocator()->log()->facade());
        };

        return $container;
    }
}
