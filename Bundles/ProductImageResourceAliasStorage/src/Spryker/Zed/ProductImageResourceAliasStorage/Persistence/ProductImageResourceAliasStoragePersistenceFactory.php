<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageResourceAliasStorage\Persistence;

use Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery;
use Orm\Zed\ProductImageStorage\Persistence\SpyProductAbstractImageStorageQuery;
use Orm\Zed\ProductImageStorage\Persistence\SpyProductConcreteImageStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductImageResourceAliasStorage\ProductImageResourceAliasStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductImageResourceAliasStorage\ProductImageResourceAliasStorageConfig getConfig()
 */
class ProductImageResourceAliasStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductImageStorage\Persistence\SpyProductAbstractImageStorageQuery
     */
    public function getProductAbstractImageStoragePropelQuery(): SpyProductAbstractImageStorageQuery
    {
        return $this->getProvidedDependency(ProductImageResourceAliasStorageDependencyProvider::PROPEL_QUERY_PRODUCT_ABSTRACT_IMAGE_STORAGE);
    }

    /**
     * @return \Orm\Zed\ProductImageStorage\Persistence\SpyProductConcreteImageStorageQuery
     */
    public function getProductConcreteImageStoragePropelQuery(): SpyProductConcreteImageStorageQuery
    {
        return $this->getProvidedDependency(ProductImageResourceAliasStorageDependencyProvider::PROPEL_QUERY_PRODUCT_CONCRETE_IMAGE_STORAGE);
    }

    /**
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery
     */
    public function getProductImageSetPropelQuery(): SpyProductImageSetQuery
    {
        return $this->getProvidedDependency(ProductImageResourceAliasStorageDependencyProvider::PROPEL_QUERY_PRODUCT_IMAGE_SET);
    }
}
