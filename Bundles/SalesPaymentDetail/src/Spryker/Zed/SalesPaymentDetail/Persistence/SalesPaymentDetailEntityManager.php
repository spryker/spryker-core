<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentDetail\Persistence;

use Generated\Shared\Transfer\SalesPaymentDetailTransfer;
use Orm\Zed\SalesPaymentDetail\Persistence\SpySalesPaymentDetail;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\SalesPaymentDetail\Persistence\SalesPaymentDetailPersistenceFactory getFactory()
 */
class SalesPaymentDetailEntityManager extends AbstractEntityManager implements SalesPaymentDetailEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesPaymentDetailTransfer $salesPaymentDetailTransfer
     *
     * @return \Generated\Shared\Transfer\SalesPaymentDetailTransfer
     */
    public function createSalesPaymentDetails(SalesPaymentDetailTransfer $salesPaymentDetailTransfer): SalesPaymentDetailTransfer
    {
        $salesPaymentDetailEntity = $this->getFactory()->createSalesPaymentDetailMapper()->mapSalesPaymentDetailTransferToSalesPaymentDetailEntity($salesPaymentDetailTransfer, new SpySalesPaymentDetail());
        $salesPaymentDetailEntity->save();

        return $salesPaymentDetailTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesPaymentDetailTransfer $salesPaymentDetailTransfer
     *
     * @return \Generated\Shared\Transfer\SalesPaymentDetailTransfer
     */
    public function updateSalesPaymentDetails(SalesPaymentDetailTransfer $salesPaymentDetailTransfer): SalesPaymentDetailTransfer
    {
        $salesPaymentDetailEntity = $this->getFactory()->createSalesPaymentDetailQuery()
            ->filterByPaymentReference($salesPaymentDetailTransfer->getPaymentReference())
            ->findOne();

        if ($salesPaymentDetailEntity === null) {
            return $salesPaymentDetailTransfer;
        }

        $salesPaymentDetailEntity->fromArray($salesPaymentDetailTransfer->toArray());
        $salesPaymentDetailEntity->save();

        return $salesPaymentDetailTransfer;
    }
}
