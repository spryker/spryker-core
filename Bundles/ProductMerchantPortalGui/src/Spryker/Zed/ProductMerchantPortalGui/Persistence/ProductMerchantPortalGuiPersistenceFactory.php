<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Persistence;

use Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductDefaultQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery;
use Orm\Zed\ProductImage\Persistence\SpyProductImageQuery;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToUtilEncodingServiceInterface;
use Spryker\Zed\ProductMerchantPortalGui\Persistence\Propel\ProductAbstractTableDataMapper;
use Spryker\Zed\ProductMerchantPortalGui\Persistence\Propel\ProductTableDataMapper;
use Spryker\Zed\ProductMerchantPortalGui\Persistence\Propel\PropelModelPagerMapper;
use Spryker\Zed\ProductMerchantPortalGui\ProductMerchantPortalGuiDependencyProvider;

/**
 * @method \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductMerchantPortalGui\ProductMerchantPortalGuiConfig getConfig()
 */
class ProductMerchantPortalGuiPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Persistence\Propel\ProductAbstractTableDataMapper
     */
    public function createProductAbstractTableDataMapper(): ProductAbstractTableDataMapper
    {
        return new ProductAbstractTableDataMapper($this->getUtilEncodingService());
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Persistence\Propel\PropelModelPagerMapper
     */
    public function createPropelModelPagerMapper(): PropelModelPagerMapper
    {
        return new PropelModelPagerMapper();
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Persistence\Propel\ProductTableDataMapper
     */
    public function createProductTableDataMapper(): ProductTableDataMapper
    {
        return new ProductTableDataMapper($this->getUtilEncodingService());
    }

    /**
     * @phpstan-return \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery<\Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract>
     *
     * @return \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery
     */
    public function getMerchantProductAbstractPropelQuery(): SpyMerchantProductAbstractQuery
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::PROPEL_QUERY_MERCHANT_PRODUCT_ABSTRACT);
    }

    /**
     * @phpstan-return \Orm\Zed\ProductImage\Persistence\SpyProductImageQuery<\Orm\Zed\ProductImage\Persistence\SpyProductImage>
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageQuery
     */
    public function getProductImagePropelQuery(): SpyProductImageQuery
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::PROPEL_QUERY_PRODUCT_IMAGE);
    }

    /**
     * @phpstan-return \Orm\Zed\Product\Persistence\SpyProductQuery<\Orm\Zed\Product\Persistence\SpyProduct>
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function getProductConcretePropelQuery(): SpyProductQuery
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::PROPEL_QUERY_PRODUCT_CONCRETE);
    }

    /**
     * @phpstan-return \Orm\Zed\Store\Persistence\SpyStoreQuery<\Orm\Zed\Store\Persistence\SpyStore>
     *
     * @return \Orm\Zed\Store\Persistence\SpyStoreQuery
     */
    public function getStorePropelQuery(): SpyStoreQuery
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::PROPEL_QUERY_STORE);
    }

    /**
     * @phpstan-return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery<\Orm\Zed\ProductCategory\Persistence\SpyProductCategory>
     *
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    public function getProductCategoryPropelQuery(): SpyProductCategoryQuery
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::PROPEL_QUERY_PRODUCT_CATEGORY);
    }

    /**
     * @phpstan-return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductDefaultQuery<\Orm\Zed\PriceProduct\Persistence\SpyPriceProductDefaultQuery>
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductDefaultQuery
     */
    public function getPriceProductDefaultPropelQuery(): SpyPriceProductDefaultQuery
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::PROPEL_QUERY_PRICE_PRODUCT_DEFAULT);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ProductMerchantPortalGuiToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface
     */
    public function getPriceProductFacade(): ProductMerchantPortalGuiToPriceProductFacadeInterface
    {
        return $this->getProvidedDependency(ProductMerchantPortalGuiDependencyProvider::FACADE_PRICE_PRODUCT);
    }
}
