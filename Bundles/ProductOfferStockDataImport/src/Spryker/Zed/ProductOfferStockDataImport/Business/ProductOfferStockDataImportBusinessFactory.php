<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStockDataImport\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductOfferStockDataImport\Dependency\Facade\ProductOfferStockDataImportToProductOfferFacadeInterface;
use Spryker\Zed\ProductOfferStockDataImport\ProductOfferStockDataImportDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOfferStockDataImport\ProductOfferStockDataImportConfig getConfig()
 */
class ProductOfferStockDataImportBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductOfferStockDataImport\Dependency\Facade\ProductOfferStockDataImportToProductOfferFacadeInterface
     */
    protected function getProductOfferFacade(): ProductOfferStockDataImportToProductOfferFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferStockDataImportDependencyProvider::FACADE_PRODUCT_OFFER);
    }
}
