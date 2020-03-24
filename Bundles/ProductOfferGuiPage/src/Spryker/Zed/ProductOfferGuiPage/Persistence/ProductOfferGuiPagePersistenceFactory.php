<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Persistence;

use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Service\ProductOfferGuiPageToUtilEncodingServiceInterface;
use Spryker\Zed\ProductOfferGuiPage\Persistence\Propel\ProductOfferTableDataMapper;
use Spryker\Zed\ProductOfferGuiPage\Persistence\Propel\ProductTableDataMapper;
use Spryker\Zed\ProductOfferGuiPage\ProductOfferGuiPageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOfferGuiPage\Persistence\ProductOfferGuiPageRepositoryInterface getRepository()
 */
class ProductOfferGuiPagePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Persistence\Propel\ProductTableDataMapper
     */
    public function createProductTableDataMapper(): ProductTableDataMapper
    {
        return new ProductTableDataMapper(
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Persistence\Propel\ProductOfferTableDataMapper
     */
    public function createProductOfferTableDataMapper(): ProductOfferTableDataMapper
    {
        return new ProductOfferTableDataMapper(
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function getProductConcretePropelQuery(): SpyProductQuery
    {
        return $this->getProvidedDependency(ProductOfferGuiPageDependencyProvider::PROPEL_QUERY_PRODUCT_CONCRETE);
    }

    /**
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    public function getProductOfferPropelQuery(): SpyProductOfferQuery
    {
        return $this->getProvidedDependency(ProductOfferGuiPageDependencyProvider::PROPEL_QUERY_PRODUCT_OFFER);
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Dependency\Service\ProductOfferGuiPageToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ProductOfferGuiPageToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ProductOfferGuiPageDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
