<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantConnector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\SalesMerchantConnector\Dependency\Facade\SalesMerchantConnectorToMerchantProductOfferFacadeBridge;

/**
 * @method \Spryker\Zed\SalesMerchantConnector\SalesMerchantConnectorConfig getConfig()
 */
class SalesMerchantConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_MERCHANT_PRODUCT_OFFER = 'FACADE_MERCHANT_PRODUCT_OFFER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addMerchantProductOfferFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function addMerchantProductOfferFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT_PRODUCT_OFFER, function (Container $container) {
            return new SalesMerchantConnectorToMerchantProductOfferFacadeBridge(
                $container->getLocator()->merchantProductOffer()->facade()
            );
        });

        return $container;
    }
}
