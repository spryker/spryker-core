<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedRequest;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ZedRequest\Dependency\Facade\NullMessenger;
use Spryker\Zed\ZedRequest\Dependency\Facade\ZedRequestToMessengerBridge;
use LogicException;

class ZedRequestDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_MESSENGER = 'messenger facade';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addMessengerFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMessengerFacade(Container $container)
    {
        $container[self::FACADE_MESSENGER] = function (Container $container) {
            try {
                $messenger = $container->getLocator()->messenger()->facade();
            } catch (LogicException $exception) {
                $messenger = new NullMessenger();
            }
            $zedRequestToMessengerBridge = new ZedRequestToMessengerBridge($messenger);

            return $zedRequestToMessengerBridge;
        };

        return $container;
    }


}
