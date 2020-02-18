<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderCustomReference\Persistence;

use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\OrderCustomReference\OrderCustomReferenceDependencyProvider;

/**
 * @method \Spryker\Zed\OrderCustomReference\Persistence\OrderCustomReferenceEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\OrderCustomReference\OrderCustomReferenceConfig getConfig()
 */
class OrderCustomReferencePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    public function getSalesOrderPropelQuery(): SpySalesOrderQuery
    {
        return $this->getProvidedDependency(OrderCustomReferenceDependencyProvider::PROPEL_QUERY_SALES_ORDER);
    }
}
