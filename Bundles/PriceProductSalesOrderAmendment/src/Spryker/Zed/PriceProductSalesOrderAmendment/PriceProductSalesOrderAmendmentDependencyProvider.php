<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSalesOrderAmendment;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\PriceProductSalesOrderAmendment\PriceProductSalesOrderAmendmentConfig getConfig()
 */
class PriceProductSalesOrderAmendmentDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const SERVICE_PRICE_PRODUCT_SALES_ORDER_AMENDMENT = 'SERVICE_PRICE_PRODUCT_SALES_ORDER_AMENDMENT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addPriceProductSalesOrderAmendmentService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceProductSalesOrderAmendmentService(Container $container): Container
    {
        $container->set(static::SERVICE_PRICE_PRODUCT_SALES_ORDER_AMENDMENT, function (Container $container) {
            return $container->getLocator()->priceProductSalesOrderAmendment()->service();
        });

        return $container;
    }
}
