<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnSearch\Persistence;

use Orm\Zed\SalesReturnSearch\Persistence\SpySalesReturnReasonSearchQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\SalesReturnSearch\SalesReturnSearchConfig getConfig()
 * @method \Spryker\Zed\SalesReturnSearch\Persistence\SalesReturnSearchEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SalesReturnSearch\Persistence\SalesReturnSearchRepositoryInterface getRepository()
 */
class SalesReturnSearchPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\SalesReturnSearch\Persistence\SpySalesReturnReasonSearchQuery
     */
    public function getSalesReturnReasonSearchPropelQuery(): SpySalesReturnReasonSearchQuery
    {
        return SpySalesReturnReasonSearchQuery::create();
    }
}
