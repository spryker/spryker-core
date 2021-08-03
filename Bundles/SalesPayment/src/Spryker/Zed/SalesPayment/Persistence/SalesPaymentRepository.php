<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPayment\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\SalesPayment\Persistence\SalesPaymentPersistenceFactory getFactory()
 */
class SalesPaymentRepository extends AbstractRepository implements SalesPaymentRepositoryInterface
{
    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\SalesPaymentTransfer[]
     */
    public function getSalesPaymentsByIdSalesOrder(int $idSalesOrder): array
    {
        $salesPayments = $this->getFactory()
            ->createSalesPaymentQuery()
            ->filterByFkSalesOrder($idSalesOrder)
            ->find();

        return $this->getFactory()
            ->createSalesPaymentMapper()
            ->mapSalesPaymentEntityCollectionToSalesPaymentTransferArray($salesPayments);
    }
}
