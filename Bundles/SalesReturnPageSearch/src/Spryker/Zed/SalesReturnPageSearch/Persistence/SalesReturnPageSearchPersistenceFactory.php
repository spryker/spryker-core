<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnPageSearch\Persistence;

use Orm\Zed\SalesReturnPageSearch\Persistence\SpySalesReturnReasonPageSearchQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\SalesReturnPageSearch\SalesReturnPageSearchConfig getConfig()
 * @method \Spryker\Zed\SalesReturnPageSearch\Persistence\SalesReturnPageSearchEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SalesReturnPageSearch\Persistence\SalesReturnPageSearchRepositoryInterface getRepository()
 */
class SalesReturnPageSearchPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\SalesReturnPageSearch\Persistence\SpySalesReturnReasonPageSearchQuery
     */
    public function getSalesReturnReasonPageSearchPropelQuery(): SpySalesReturnReasonPageSearchQuery
    {
        return SpySalesReturnReasonPageSearchQuery::create();
    }
}
