<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedRequest;

use LogicException;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ZedRequest\Dependency\Facade\NullMessenger;
use Spryker\Zed\ZedRequest\Dependency\Facade\ZedRequestToMessengerBridge;
use Spryker\Zed\ZedRequest\Dependency\Facade\ZedRequestToStoreBridge;

/**
 * @method \Spryker\Zed\ZedRequest\ZedRequestConfig getConfig()
 */
class ZedRequestDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_MESSENGER = 'messenger facade';

    /**
     * @var string
     */
    public const STORE = 'STORE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addMessengerFacade($container);
        $container = $this->addStore($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMessengerFacade(Container $container)
    {
        $container->set(static::FACADE_MESSENGER, function (Container $container) {
            try {
                $messenger = $container->getLocator()->messenger()->facade();
            } catch (LogicException $exception) {
                /** @var \Spryker\Zed\Messenger\Business\MessengerFacadeInterface $messenger */
                $messenger = new NullMessenger();
            }
            $zedRequestToMessengerBridge = new ZedRequestToMessengerBridge($messenger);

            return $zedRequestToMessengerBridge;
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStore(Container $container)
    {
        $container->set(static::STORE, function () {
            return new ZedRequestToStoreBridge(Store::getInstance());
        });

        return $container;
    }
}
