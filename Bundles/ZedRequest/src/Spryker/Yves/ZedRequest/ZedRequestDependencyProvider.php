<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\ZedRequest;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\ZedRequest\Dependency\Service\ZedRequestToUtilNetworkBridge;

class ZedRequestDependencyProvider extends AbstractBundleDependencyProvider
{

    const SERVICE_UTIL_NETWORK = 'util network service';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addUtilNetworkService($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addUtilNetworkService(Container $container)
    {
        $container[self::SERVICE_UTIL_NETWORK] = function (Container $container) {
            return new ZedRequestToUtilNetworkBridge($container->getLocator()->utilNetwork()->service());
        };

        return $container;
    }


}
