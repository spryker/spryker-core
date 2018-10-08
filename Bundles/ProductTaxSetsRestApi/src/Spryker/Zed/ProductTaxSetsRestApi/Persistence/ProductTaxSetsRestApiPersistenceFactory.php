<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductTaxSetsRestApi\Persistence;

use Orm\Zed\Tax\Persistence\SpyTaxSetQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductTaxSetsRestApi\ProductTaxSetsRestApiDependencyProvider;

/**
 * @method \Spryker\Zed\ProductTaxSetsRestApi\ProductTaxSetsRestApiConfig getConfig()
 */
class ProductTaxSetsRestApiPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Tax\Persistence\SpyTaxSetQuery
     */
    public function getTaxSetPropelQuery(): SpyTaxSetQuery
    {
        return $this->getProvidedDependency(ProductTaxSetsRestApiDependencyProvider::PROPEL_QUERY_TAX_SET);
    }
}
