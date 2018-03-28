<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySupplier\Persistence;

use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\CompanySupplier\Persistence\CompanySupplierPersistenceFactory getFactory()
 */
class CompanySupplierQueryContainer extends AbstractQueryContainer implements CompanySupplierQueryContainerInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function queryProductSuppliers(): SpyProductQuery
    {
        $query = $this->getFactory()->createProductQueryContainer();
        $query->rightJoinSpyCompanySupplierToProduct();

        return $query;
    }
}
