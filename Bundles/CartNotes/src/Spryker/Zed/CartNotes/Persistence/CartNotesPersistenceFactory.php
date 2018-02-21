<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartNotes\Persistence;

use Spryker\Zed\CartNotes\CartNotesDependencyProvider;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

class CartNotesPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    public function getSalesOrderQuery()
    {
        return $this->getProvidedDependency(CartNotesDependencyProvider::SALES_ORDER_QUERY);
    }
}
