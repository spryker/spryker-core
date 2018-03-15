<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySupplierGui\Dependency\QueryContainer;

use Orm\Zed\Product\Persistence\SpyProductQuery;

interface CompanySupplierGuiToCompanySupplierQueryContainerInterface
{
    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function queryProductSuppliers(): SpyProductQuery;
}
