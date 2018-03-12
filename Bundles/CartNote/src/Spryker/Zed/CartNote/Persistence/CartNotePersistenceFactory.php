<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartNote\Persistence;

use Spryker\Zed\CartNote\CartNoteDependencyProvider;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

class CartNotePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    public function getSalesOrderQuery()
    {
        return $this->getProvidedDependency(CartNoteDependencyProvider::SALES_ORDER_QUERY);
    }
}
