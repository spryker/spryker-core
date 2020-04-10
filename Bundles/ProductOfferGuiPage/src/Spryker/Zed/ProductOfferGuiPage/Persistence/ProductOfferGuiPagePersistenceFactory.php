<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Persistence;

use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductImage\Persistence\SpyProductImageQuery;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferStoreQuery;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
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
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageQuery
     */
    public function getProductImagePropelQuery(): SpyProductImageQuery
    {
        return $this->getProvidedDependency(ProductOfferGuiPageDependencyProvider::PROPEL_QUERY_PRODUCT_IMAGE);
    }

    /**
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    public function getProductOfferPropelQuery(): SpyProductOfferQuery
    {
        return $this->getProvidedDependency(ProductOfferGuiPageDependencyProvider::PROPEL_QUERY_PRODUCT_OFFER);
    }

    /**
     * @return \Orm\Zed\Store\Persistence\SpyStoreQuery
     */
    public function getStorePropelQuery(): SpyStoreQuery
    {
        return $this->getProvidedDependency(ProductOfferGuiPageDependencyProvider::PROPEL_QUERY_STORE);
    }

    /**
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferStoreQuery
     */
    public function getProductOfferStorePropelQuery(): SpyProductOfferStoreQuery
    {
        return $this->getProvidedDependency(ProductOfferGuiPageDependencyProvider::PROPEL_QUERY_PRODUCT_OFFER_STORE);
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Dependency\Service\ProductOfferGuiPageToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ProductOfferGuiPageToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ProductOfferGuiPageDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
