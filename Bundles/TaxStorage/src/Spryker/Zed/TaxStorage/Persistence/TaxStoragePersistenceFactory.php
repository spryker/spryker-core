<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxStorage\Persistence;

use Orm\Zed\Tax\Persistence\SpyTaxSetQuery;
use Orm\Zed\TaxStorage\Persistence\SpyTaxSetStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\TaxStorage\TaxStorageDependencyProvider;

/**
 * @method \Spryker\Zed\TaxStorage\Persistence\TaxStorageEntityManager getEntityManager()
 * @method \Spryker\Zed\TaxStorage\Persistence\TaxStorageRepository getRepository()
 * @method \Spryker\Zed\TaxStorage\TaxStorageConfig getConfig()
 */
class TaxStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Tax\Persistence\SpyTaxSetTaxQuery
     */
    public function createTaxSetQuery(): SpyTaxSetQuery
    {
        return $this->getProvidedDependency(TaxStorageDependencyProvider::PROPEL_QUERY_TAX_SET);
    }

    /**
     * @return \Orm\Zed\Tax\Persistence\SpyTaxSetTaxQuery
     */
    public function createTaxSetStorageQuery(): SpyTaxSetStorageQuery
    {
        return SpyTaxSetStorageQuery::create();
    }
}
