<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySupplierGui\Dependency\QueryContainer;

use Orm\Zed\Product\Persistence\SpyProductQuery;

class CompanySupplierGuiToCompanySupplierQueryContainerBridge implements CompanySupplierGuiToCompanySupplierQueryContainerInterface
{
    /**
     * @var \Spryker\Zed\CompanySupplier\Persistence\CompanySupplierQueryContainerInterface
     */
    protected $companySupplierQueryContainer;

    /**
     * @param \Spryker\Zed\CompanySupplier\Persistence\CompanySupplierQueryContainerInterface $companySupplierQueryContainer
     */
    public function __construct($companySupplierQueryContainer)
    {
        $this->companySupplierQueryContainer = $companySupplierQueryContainer;
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function queryProductSuppliers(): SpyProductQuery
    {
        return $this->companySupplierQueryContainer->queryProductSuppliers();
    }
}
