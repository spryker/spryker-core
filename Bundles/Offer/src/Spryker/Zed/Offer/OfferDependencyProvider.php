<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Offer\Communication\Plugin\OfferSavingHydratorPlugin;
use Spryker\Zed\Offer\Dependency\Facade\OfferToCustomerFacadeBridge;
use Spryker\Zed\Offer\Dependency\Facade\OfferToMessengerFacadeBridge;
use Spryker\Zed\Offer\Dependency\Facade\OfferToSalesFacadeBridge;
use Spryker\Zed\Offer\Dependency\Service\OfferToUtilEncodingServiceBridge;

class OfferDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_SALES = 'FACADE_SALES';
    public const FACADE_MESSENGER = 'FACADE_MESSENGER';
    public const FACADE_CUSTOMER = 'FACADE_CUSTOMER';
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';
    public const PLUGINS_OFFER_HYDRATOR = 'PLUGINS_OFFER_HYDRATOR';
    public const PLUGINS_OFFER_DO_UPDATE = 'PLUGINS_OFFER_DO_UPDATE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addSalesFacade($container);
        $container = $this->addCustomerFacade($container);
        $container = $this->addMessengerFacade($container);
        $container = $this->addOfferHydratorPlugins($container);
        $container = $this->addOfferDoUpdatePlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addMessengerFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container = $this->addServiceUtilEncoding($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesFacade(Container $container): Container
    {
        $container[static::FACADE_SALES] = function (Container $container) {
            return new OfferToSalesFacadeBridge(
                $container->getLocator()->sales()->facade()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMessengerFacade(Container $container): Container
    {
        $container[static::FACADE_MESSENGER] = function (Container $container) {
            return new OfferToMessengerFacadeBridge($container->getLocator()->messenger()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCustomerFacade(Container $container): Container
    {
        $container[static::FACADE_CUSTOMER] = function (Container $container) {
            return new OfferToCustomerFacadeBridge($container->getLocator()->customer()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOfferHydratorPlugins(Container $container)
    {
        $container[static::PLUGINS_OFFER_HYDRATOR] = function (Container $container) {
            return $this->getOfferHydratorPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addServiceUtilEncoding(Container $container)
    {
        $container[static::SERVICE_UTIL_ENCODING] = function (Container $container) {
            return new OfferToUtilEncodingServiceBridge($container->getLocator()->utilEncoding()->service());
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\OfferExtension\Dependency\Plugin\OfferHydratorPluginInterface[]
     */
    protected function getOfferHydratorPlugins(): array
    {
        return [
            new OfferSavingHydratorPlugin(),
        ];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOfferDoUpdatePlugins(Container $container)
    {
        $container[static::PLUGINS_OFFER_DO_UPDATE] = function (Container $container) {
            return $this->getOfferDoUpdatePlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\Offer\Dependency\Plugin\OfferDoUpdatePluginInterface[]
     */
    protected function getOfferDoUpdatePlugins(): array
    {
        return [];
    }
}
