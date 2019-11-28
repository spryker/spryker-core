<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantProductOfferStorage\Business\ProductConcreteOffersStorage\ProductConcreteOffersStorageWriter;
use Spryker\Zed\MerchantProductOfferStorage\Business\ProductConcreteOffersStorage\ProductConcreteOffersStorageWriterInterface;
use Spryker\Zed\MerchantProductOfferStorage\Business\ProductOfferStorage\ProductOfferStorageWriter;
use Spryker\Zed\MerchantProductOfferStorage\Business\ProductOfferStorage\ProductOfferStorageWriterInterface;
use Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToProductOfferFacadeInterface;
use Spryker\Zed\MerchantProductOfferStorage\MerchantProductOfferStorageDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantProductOfferStorage\MerchantProductOfferStorageConfig getConfig()
 */
class MerchantProductOfferStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantProductOfferStorage\Business\ProductConcreteOffersStorage\ProductConcreteOffersStorageWriterInterface
     */
    public function createProductConcreteProductOffersStorageWriter(): ProductConcreteOffersStorageWriterInterface
    {
        return new ProductConcreteOffersStorageWriter($this->getProductOfferFacade());
    }

    /**
     * @return \Spryker\Zed\MerchantProductOfferStorage\Business\ProductOfferStorage\ProductOfferStorageWriterInterface
     */
    public function createProductOfferStorageWriter(): ProductOfferStorageWriterInterface
    {
        return new ProductOfferStorageWriter($this->getProductOfferFacade());
    }

    /**
     * @return \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToProductOfferFacadeInterface
     */
    public function getProductOfferFacade(): MerchantProductOfferStorageToProductOfferFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProductOfferStorageDependencyProvider::FACADE_PRODUCT_OFFER);
    }
}
