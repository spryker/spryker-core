<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductStorage\Persistence;

use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\TaxProductStorage\Persistence\SpyTaxProductStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\TaxProductStorage\Persistence\Propel\Mapper\TaxProductStorageMapper;
use Spryker\Zed\TaxProductStorage\TaxProductStorageDependencyProvider;

/**
 * @method \Spryker\Zed\TaxProductStorage\TaxProductStorageConfig getConfig()
 * @method \Spryker\Zed\TaxProductStorage\Persistence\TaxProductStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\TaxProductStorage\Persistence\TaxProductStorageRepositoryInterface getRepository()
 */
class TaxProductStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\TaxProductStorage\Persistence\SpyTaxProductStorageQuery
     */
    public function createTaxProductStorageQuery(): SpyTaxProductStorageQuery
    {
        return SpyTaxProductStorageQuery::create();
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function getProductAbstractQuery(): SpyProductAbstractQuery
    {
        return $this->getProvidedDependency(TaxProductStorageDependencyProvider::PROPEL_QUERY_PRODUCT_ABSTRACT);
    }

    /**
     * @return \Spryker\Zed\TaxProductStorage\Persistence\Propel\Mapper\TaxProductStorageMapper
     */
    public function createTaxProductStorageMapper(): TaxProductStorageMapper
    {
        return new TaxProductStorageMapper();
    }
}
