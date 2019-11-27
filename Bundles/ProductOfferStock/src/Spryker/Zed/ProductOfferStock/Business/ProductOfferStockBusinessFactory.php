<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStock\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductOfferStock\Business\ProductOfferStock\ProductOfferStockReader;
use Spryker\Zed\ProductOfferStock\Business\ProductOfferStock\ProductOfferStockReaderInterface;
use Spryker\Zed\ProductOfferStock\Dependency\Facade\ProductOfferStockToAvailabilityFacadeInterface;
use Spryker\Zed\ProductOfferStock\ProductOfferStockDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOfferStock\ProductOfferStockConfig getConfig()
 * @method \Spryker\Zed\ProductOfferStock\Persistence\ProductOfferStockRepositoryInterface getRepository()
 */
class ProductOfferStockBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductOfferStock\Business\ProductOfferStock\ProductOfferStockReaderInterface
     */
    public function createProductOfferStockReader(): ProductOfferStockReaderInterface
    {
        return new ProductOfferStockReader($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\ProductOfferStock\Dependency\Facade\ProductOfferStockToAvailabilityFacadeInterface
     */
    public function getAvailabilityFacade(): ProductOfferStockToAvailabilityFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferStockDependencyProvider::FACADE_AVAILABILITY);
    }
}
