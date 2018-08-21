<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxSetsRestApi\Persistence;

use Orm\Zed\Tax\Persistence\SpyTaxSetQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\TaxSetsRestApi\TaxSetsRestApiDependencyProvider;

/**
 * @method \Spryker\Zed\TaxSetsRestApi\TaxSetsRestApiConfig getConfig()
 * @method \Spryker\Zed\TaxSetsRestApi\Persistence\TaxSetsRestApiEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\TaxSetsRestApi\Persistence\TaxSetsRestApiRepositoryInterface getRepository()
 */
class TaxSetsRestApiPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Tax\Persistence\SpyTaxSetQuery
     */
    public function getTaxSetPropelQuery(): SpyTaxSetQuery
    {
        return $this->getProvidedDependency(TaxSetsRestApiDependencyProvider::PROPEL_QUERY_TAX_SET);
    }

    /**
     * @return \Spryker\Zed\TaxSetsRestApi\Persistence\TaxSetsRestApiRepositoryInterface
     */
    public function getTaxSetsRestApiRepository(): TaxSetsRestApiRepositoryInterface
    {
        return $this->getRepository();
    }
}
