<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\NewRelic;

use Spryker\Shared\NewRelicApi\NewRelicApi;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class NewRelicDependencyProvider extends AbstractBundleDependencyProvider
{
    public const NEW_RELIC_API = 'new relic api';
    public const SERVICE_NETWORK = 'util network service';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addNewRelicApi($container);
        $container = $this->addUtilNetworkService($container);

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
    protected function addUtilNetworkService(Container $container)
    {
        $container[static::SERVICE_NETWORK] = function (Container $container) {
            return $container->getLocator()->utilNetwork()->service();
        };

        return $container;
    }
}
