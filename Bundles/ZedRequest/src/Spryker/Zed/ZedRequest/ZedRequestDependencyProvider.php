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
     * @deprecated Will be removed after dynamic multi-store is always enabled.
     *
     * @var string
     */
    public const DYNAMIC_STORE_MODE = 'DYNAMIC_STORE_MODE';

    /**
     * @var string
     */
    public const FACADE_MESSENGER = 'messenger facade';

    /**
     * @deprecated Will be removed after dynamic multi-store is always enabled.
     *
     * @var string
     */
    public const STORE = 'STORE';

    /**
     * @uses \Spryker\Zed\Http\Communication\Plugin\Application\HttpApplicationPlugin::SERVICE_REQUEST_STACK
     *
     * @var string
     */
    public const SERVICE_REQUEST_STACK = 'request_stack';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addMessengerFacade($container);
        $container = $this->addStore($container);
        $container = $this->addDynamicStoreMode($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addRequestStack($container);

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
     * @deprecated will be removed in next major.
     *
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

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addRequestStack(Container $container): Container
    {
        $container->set(static::SERVICE_REQUEST_STACK, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_REQUEST_STACK);
        });

        return $container;
    }

    /**
     * @deprecated Will be removed after dynamic multi-store is always enabled.
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addDynamicStoreMode(Container $container): Container
    {
        $container->set(static::DYNAMIC_STORE_MODE, function () {
            return $this->isDynamicStoreModeEnabled();
        });

        return $container;
    }

    /**
     * @deprecated Will be removed after dynamic multi-store is always enabled.
     *
     * @return bool
     */
    protected function isDynamicStoreModeEnabled(): bool
    {
        return Store::isDynamicStoreMode();
    }
}
