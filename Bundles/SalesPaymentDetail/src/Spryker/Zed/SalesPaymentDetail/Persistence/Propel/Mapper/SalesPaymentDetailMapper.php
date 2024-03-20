<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentDetail\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\SalesPaymentDetailTransfer;
use Orm\Zed\SalesPaymentDetail\Persistence\SpySalesPaymentDetail;

class SalesPaymentDetailMapper
{
    /**
     * @param \Generated\Shared\Transfer\SalesPaymentDetailTransfer $salesPaymentDetailTransfer
     * @param \Orm\Zed\SalesPaymentDetail\Persistence\SpySalesPaymentDetail $salesPaymentDetailEntity
     *
     * @return \Orm\Zed\SalesPaymentDetail\Persistence\SpySalesPaymentDetail
     */
    public function mapSalesPaymentDetailTransferToSalesPaymentDetailEntity(
        SalesPaymentDetailTransfer $salesPaymentDetailTransfer,
        SpySalesPaymentDetail $salesPaymentDetailEntity
    ): SpySalesPaymentDetail {
        return $salesPaymentDetailEntity->fromArray($salesPaymentDetailTransfer->toArray());
    }

    /**
     * @param \Orm\Zed\SalesPaymentDetail\Persistence\SpySalesPaymentDetail $salesPaymentDetailEntity
     * @param \Generated\Shared\Transfer\SalesPaymentDetailTransfer $salesPaymentDetailTransfer
     *
     * @return \Generated\Shared\Transfer\SalesPaymentDetailTransfer
     */
    public function mapSalesPaymentDetailEntityToSalesPaymentDetailTransfer(
        SpySalesPaymentDetail $salesPaymentDetailEntity,
        SalesPaymentDetailTransfer $salesPaymentDetailTransfer
    ): SalesPaymentDetailTransfer {
        return $salesPaymentDetailTransfer->fromArray($salesPaymentDetailEntity->toArray(), true);
    }
}
