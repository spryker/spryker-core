<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Persistence;

use Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery;
use Orm\Zed\ProductLabelStorage\Persistence\SpyProductAbstractLabelStorageQuery;
use Orm\Zed\ProductLabelStorage\Persistence\SpyProductLabelDictionaryStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductLabelStorage\Dependency\QueryContainer\ProductLabelStorageToProductLabelQueryContainerInterface;
use Spryker\Zed\ProductLabelStorage\Persistence\Mapper\ProductAbstractLabelStorageMapper;
use Spryker\Zed\ProductLabelStorage\Persistence\Mapper\ProductLabelDictionaryStorageMapper;
use Spryker\Zed\ProductLabelStorage\ProductLabelStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductLabelStorage\ProductLabelStorageConfig getConfig()
 * @method \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageRepositoryInterface getRepository()
 */
class ProductLabelStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductLabelStorage\Persistence\SpyProductAbstractLabelStorageQuery
     */
    public function createSpyProductAbstractLabelStorageQuery()
    {
        return SpyProductAbstractLabelStorageQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductLabelStorage\Persistence\SpyProductLabelDictionaryStorageQuery
     */
    public function createSpyProductLabelDictionaryStorageQuery()
    {
        return SpyProductLabelDictionaryStorageQuery::create();
    }

    /**
     * @return \Spryker\Zed\ProductLabelStorage\Persistence\Mapper\ProductAbstractLabelStorageMapper
     */
    public function createProductAbstractLabelStorageMapper(): ProductAbstractLabelStorageMapper
    {
        return new ProductAbstractLabelStorageMapper();
    }

    /**
     * @return \Spryker\Zed\ProductLabelStorage\Persistence\Mapper\ProductLabelDictionaryStorageMapper
     */
    public function createProductLabelDictionaryStorageMapper(): ProductLabelDictionaryStorageMapper
    {
        return new ProductLabelDictionaryStorageMapper();
    }

    /**
     * @return \Spryker\Zed\ProductLabelStorage\Dependency\QueryContainer\ProductLabelStorageToProductLabelQueryContainerInterface
     */
    public function getProductLabelQueryContainer(): ProductLabelStorageToProductLabelQueryContainerInterface
    {
        return $this->getProvidedDependency(ProductLabelStorageDependencyProvider::QUERY_CONTAINER_PRODUCT_LABEL);
    }

    /**
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    public function getProductLabelPropelQuery(): SpyProductLabelQuery
    {
        return $this->getProvidedDependency(ProductLabelStorageDependencyProvider::PROPEL_QUERY_PRODUCT_LABEL);
    }
}
