<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductSalesOrderAmendment;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

/**
 * @method \Spryker\Client\PriceProductSalesOrderAmendment\PriceProductSalesOrderAmendmentConfig getConfig()
 */
class PriceProductSalesOrderAmendmentDependencyProvider extends AbstractDependencyProvider
{
    /**
     * @var string
     */
    public const SERVICE_PRICE_PRODUCT_SALES_ORDER_AMENDMENT = 'SERVICE_PRICE_PRODUCT_SALES_ORDER_AMENDMENT';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);
        $container = $this->addPriceProductSalesOrderAmendmentService($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addPriceProductSalesOrderAmendmentService(Container $container): Container
    {
        $container->set(static::SERVICE_PRICE_PRODUCT_SALES_ORDER_AMENDMENT, function (Container $container) {
            return $container->getLocator()->priceProductSalesOrderAmendment()->service();
        });

        return $container;
    }
}
