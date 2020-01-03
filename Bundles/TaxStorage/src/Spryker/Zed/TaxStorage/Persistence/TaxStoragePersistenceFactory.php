<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxStorage\Persistence;

use Orm\Zed\Tax\Persistence\SpyTaxSetQuery;
use Orm\Zed\TaxStorage\Persistence\SpyTaxSetStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\TaxStorage\Persistence\Propel\Mapper\TaxStorageMapper;
use Spryker\Zed\TaxStorage\TaxStorageDependencyProvider;

/**
 * @method \Spryker\Zed\TaxStorage\Persistence\TaxStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\TaxStorage\Persistence\TaxStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\TaxStorage\TaxStorageConfig getConfig()
 */
class TaxStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Tax\Persistence\SpyTaxSetQuery
     */
    public function getTaxSetQuery(): SpyTaxSetQuery
    {
        return $this->getProvidedDependency(TaxStorageDependencyProvider::PROPEL_QUERY_TAX_SET);
    }

    /**
     * @return \Orm\Zed\TaxStorage\Persistence\SpyTaxSetStorageQuery
     */
    public function createTaxSetStorageQuery(): SpyTaxSetStorageQuery
    {
        return SpyTaxSetStorageQuery::create();
    }

    /**
     * @return \Spryker\Zed\TaxStorage\Persistence\Propel\Mapper\TaxStorageMapper
     */
    public function createTaxStorageMapper()
    {
        return new TaxStorageMapper();
    }
}
