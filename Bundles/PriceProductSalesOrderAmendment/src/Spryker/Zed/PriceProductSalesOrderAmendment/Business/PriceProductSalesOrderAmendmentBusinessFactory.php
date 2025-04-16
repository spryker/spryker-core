<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSalesOrderAmendment\Business;

use Spryker\Service\PriceProductSalesOrderAmendment\PriceProductSalesOrderAmendmentServiceInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\PriceProductSalesOrderAmendment\Business\Hydrator\CartReorderItemHydrator;
use Spryker\Zed\PriceProductSalesOrderAmendment\Business\Hydrator\CartReorderItemHydratorInterface;
use Spryker\Zed\PriceProductSalesOrderAmendment\Business\Replacer\CartChangeReplacer;
use Spryker\Zed\PriceProductSalesOrderAmendment\Business\Replacer\CartChangeReplacerInterface;
use Spryker\Zed\PriceProductSalesOrderAmendment\Business\Replacer\PriceReplacer;
use Spryker\Zed\PriceProductSalesOrderAmendment\Business\Replacer\PriceReplacerInterface;
use Spryker\Zed\PriceProductSalesOrderAmendment\PriceProductSalesOrderAmendmentDependencyProvider;

/**
 * @method \Spryker\Zed\PriceProductSalesOrderAmendment\PriceProductSalesOrderAmendmentConfig getConfig()
 */
class PriceProductSalesOrderAmendmentBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\PriceProductSalesOrderAmendment\Business\Hydrator\CartReorderItemHydratorInterface
     */
    public function createCartReorderItemHydrator(): CartReorderItemHydratorInterface
    {
        return new CartReorderItemHydrator($this->getPriceProductSalesOrderAmendmentService());
    }

    /**
     * @return \Spryker\Zed\PriceProductSalesOrderAmendment\Business\Replacer\CartChangeReplacerInterface
     */
    public function createCartChangeReplacer(): CartChangeReplacerInterface
    {
        return new CartChangeReplacer(
            $this->getPriceProductSalesOrderAmendmentService(),
            $this->createPriceReplacer(),
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductSalesOrderAmendment\Business\Replacer\PriceReplacerInterface
     */
    public function createPriceReplacer(): PriceReplacerInterface
    {
        return new PriceReplacer($this->getPriceProductSalesOrderAmendmentService());
    }

    /**
     * @return \Spryker\Service\PriceProductSalesOrderAmendment\PriceProductSalesOrderAmendmentServiceInterface
     */
    public function getPriceProductSalesOrderAmendmentService(): PriceProductSalesOrderAmendmentServiceInterface
    {
        return $this->getProvidedDependency(PriceProductSalesOrderAmendmentDependencyProvider::SERVICE_PRICE_PRODUCT_SALES_ORDER_AMENDMENT);
    }
}
