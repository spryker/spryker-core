<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NewRelic;

use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Library\System;
use Spryker\Shared\NewRelic\NewRelicApi;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class NewRelicDependencyProvider extends AbstractBundleDependencyProvider
{

    const NEW_RELIC_API = 'new relic api';
    const STORE = 'store';
    const SYSTEM = 'system';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addNewRelicApi($container);
        $container = $this->addStore($container);
        $container = $this->addSystem($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addNewRelicApi(Container $container)
    {
        $container[static::NEW_RELIC_API] = function () {
            return new NewRelicApi();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStore(Container $container)
    {
        $container[static::STORE] = function () {
            return Store::getInstance();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSystem(Container $container)
    {
        $container[static::SYSTEM] = function () {
            return new System();
        };

        return $container;
    }

}
