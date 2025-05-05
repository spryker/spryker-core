<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesQuoteRequestConnector\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\SalesQuoteRequestConnector\Persistence\SalesQuoteRequestConnectorPersistenceFactory getFactory()
 */
class SalesQuoteRequestConnectorEntityManager extends AbstractEntityManager implements SalesQuoteRequestConnectorEntityManagerInterface
{
    /**
     * @param int $idSalesOrder
     * @param string $quoteRequestVersionReference
     *
     * @return void
     */
    public function saveOrderQuoteRequestVersionReference(int $idSalesOrder, string $quoteRequestVersionReference): void
    {
        $salesOrderEntity = $this->getFactory()
            ->getSalesOrderPropelQuery()
            ->findOneByIdSalesOrder($idSalesOrder);

        if (!$salesOrderEntity) {
            return;
        }

        $salesOrderEntity->setQuoteRequestVersionReference($quoteRequestVersionReference);
        $salesOrderEntity->save();
    }
}
