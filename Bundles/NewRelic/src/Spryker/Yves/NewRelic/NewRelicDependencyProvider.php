<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\NewRelic;

use Spryker\Shared\Library\System;
use Spryker\Shared\NewRelic\NewRelicApi;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class NewRelicDependencyProvider extends AbstractBundleDependencyProvider
{

    const NEW_RELIC_API = 'new relic api';
    const SYSTEM = 'system';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addNewRelicApi($container);
        $container = $this->addSystem($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addNewRelicApi(Container $container)
    {
        $container[static::NEW_RELIC_API] = function () {
            return new NewRelicApi();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSystem(Container $container)
    {
        $container[static::SYSTEM] = function () {
            return new System();
        };

        return $container;
    }

}
