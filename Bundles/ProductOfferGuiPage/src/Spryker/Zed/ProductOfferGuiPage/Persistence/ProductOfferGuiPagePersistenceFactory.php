<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Persistence;

use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Service\ProductOfferGuiPageToUtilEncodingServiceInterface;
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
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function getProductConcretePropelQuery(): SpyProductQuery
    {
        return $this->getProvidedDependency(ProductOfferGuiPageDependencyProvider::PROPEL_QUERY_PRODUCT_CONCRETE);
    }

    /**
     * @return \Spryker\Zed\ProductOfferGuiPage\Dependency\Service\ProductOfferGuiPageToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ProductOfferGuiPageToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ProductOfferGuiPageDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
