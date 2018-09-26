<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageStorage\Persistence;

use Orm\Zed\ProductImageStorage\Persistence\SpyProductAbstractImageStorageQuery;
use Orm\Zed\ProductImageStorage\Persistence\SpyProductConcreteImageStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductImageStorage\ProductImageStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductImageStorage\ProductImageStorageConfig getConfig()
 * @method \Spryker\Zed\ProductImageStorage\Persistence\ProductImageStorageQueryContainerInterface getQueryContainer()
 */
class ProductImageStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\ProductImageStorage\Dependency\QueryContainer\ProductImageStorageToProductQueryContainerInterface
     */
    public function getProductQueryContainer()
    {
        return $this->getProvidedDependency(ProductImageStorageDependencyProvider::QUERY_CONTAINER_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductImageStorage\Dependency\QueryContainer\ProductImageStorageToProductImageQueryContainerInterface
     */
    public function getProductImageQueryContainer()
    {
        return $this->getProvidedDependency(ProductImageStorageDependencyProvider::QUERY_CONTAINER_PRODUCT_IMAGE);
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductLocalizedAttributesQuery
     */
    public function getProductLocalizedAttributesQuery()
    {
        return $this->getProvidedDependency(ProductImageStorageDependencyProvider::PROPEL_QUERY_PRODUCT_LOCALIZED_ATTRIBUTES);
    }

    /**
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery
     */
    public function getProductImageSetQuery()
    {
        return $this->getProvidedDependency(ProductImageStorageDependencyProvider::PROPEL_QUERY_PRODUCT_IMAGE_SET);
    }

    /**
     * @return \Orm\Zed\ProductImageStorage\Persistence\SpyProductAbstractImageStorageQuery
     */
    public function createSpyProductAbstractImageStorageQuery()
    {
        return SpyProductAbstractImageStorageQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductImageStorage\Persistence\SpyProductConcreteImageStorageQuery
     */
    public function createSpyProductConcreteImageStorageQuery()
    {
        return SpyProductConcreteImageStorageQuery::create();
    }
}
