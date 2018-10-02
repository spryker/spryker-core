<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Propel\Dependency\Facade\PropelToLogBridge;
use Spryker\Zed\Propel\Dependency\Service\PropelToUtilTextServiceBridge;

class PropelDependencyProvider extends AbstractBundleDependencyProvider
{
    public const UTIL_TEXT_SERVICE = 'util text service';
    public const FACADE_LOG = 'FACADE_LOG';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addUtilTextService($container);

        return $container;
    }

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
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilTextService(Container $container)
    {
        $container[static::UTIL_TEXT_SERVICE] = function () use ($container) {
            return new PropelToUtilTextServiceBridge($container->getLocator()->utilText()->service());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLogFacade(Container $container)
    {
        $container[static::FACADE_LOG] = function () use ($container) {
            return new PropelToLogBridge($container->getLocator()->log()->facade());
        };

        return $container;
    }
}
