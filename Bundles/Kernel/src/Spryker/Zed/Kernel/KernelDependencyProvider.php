<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel;

use Spryker\Zed\Kernel\Dependency\Facade\KernelToMessengerBridge;

class KernelDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_MESSENGER = 'messenger facade';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::FACADE_MESSENGER] = function (Container $container) {
            return new KernelToMessengerBridge($container->getLocator()->messenger()->facade());
        };

        return $container;
    }

}
