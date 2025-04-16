<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductSalesOrderAmendment;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\PriceProductSalesOrderAmendment\PriceResolver\OrderAmendmentPriceResolver;
use Spryker\Client\PriceProductSalesOrderAmendment\PriceResolver\OrderAmendmentPriceResolverInterface;
use Spryker\Service\PriceProductSalesOrderAmendment\PriceProductSalesOrderAmendmentServiceInterface;

/**
 * @method \Spryker\Client\PriceProductSalesOrderAmendment\PriceProductSalesOrderAmendmentConfig getConfig()
 */
class PriceProductSalesOrderAmendmentFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\PriceProductSalesOrderAmendment\PriceResolver\OrderAmendmentPriceResolverInterface
     */
    public function createOrderAmendmentPriceResolver(): OrderAmendmentPriceResolverInterface
    {
        return new OrderAmendmentPriceResolver(
            $this->getPriceProductSalesOrderAmendmentService(),
        );
    }

    /**
     * @return \Spryker\Service\PriceProductSalesOrderAmendment\PriceProductSalesOrderAmendmentServiceInterface
     */
    public function getPriceProductSalesOrderAmendmentService(): PriceProductSalesOrderAmendmentServiceInterface
    {
        return $this->getProvidedDependency(PriceProductSalesOrderAmendmentDependencyProvider::SERVICE_PRICE_PRODUCT_SALES_ORDER_AMENDMENT);
    }
}
