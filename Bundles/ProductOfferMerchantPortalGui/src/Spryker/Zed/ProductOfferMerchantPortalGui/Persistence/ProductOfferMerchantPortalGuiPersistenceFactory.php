<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Persistence;

use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductImage\Persistence\SpyProductImageQuery;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferStoreQuery;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\Propel\ProductOfferTableDataMapper;
use Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\Propel\ProductTableDataMapper;
use Spryker\Zed\ProductOfferMerchantPortalGui\ProductOfferMerchantPortalGuiDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\ProductOfferMerchantPortalGuiConfig getConfig()
 */
class ProductOfferMerchantPortalGuiPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\Propel\ProductTableDataMapper
     */
    public function createProductTableDataMapper(): ProductTableDataMapper
    {
        return new ProductTableDataMapper(
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\Propel\ProductOfferTableDataMapper
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
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::PROPEL_QUERY_PRODUCT_CONCRETE);
    }

    /**
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageQuery
     */
    public function getProductImagePropelQuery(): SpyProductImageQuery
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::PROPEL_QUERY_PRODUCT_IMAGE);
    }

    /**
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    public function getProductOfferPropelQuery(): SpyProductOfferQuery
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::PROPEL_QUERY_PRODUCT_OFFER);
    }

    /**
     * @return \Orm\Zed\Store\Persistence\SpyStoreQuery
     */
    public function getStorePropelQuery(): SpyStoreQuery
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::PROPEL_QUERY_STORE);
    }

    /**
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferStoreQuery
     */
    public function getProductOfferStorePropelQuery(): SpyProductOfferStoreQuery
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::PROPEL_QUERY_PRODUCT_OFFER_STORE);
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
