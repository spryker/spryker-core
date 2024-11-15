<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Business\Mapper;

use Generated\Shared\Transfer\SalesOrderAmendmentRequestTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentTransfer;

class SalesOrderAmendmentMapper implements SalesOrderAmendmentMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentRequestTransfer $salesOrderAmendmentRequestTransfer
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentTransfer
     */
    public function mapSalesOrderAmendmentRequestTransferToSalesOrderAmendmentTransfer(
        SalesOrderAmendmentRequestTransfer $salesOrderAmendmentRequestTransfer,
        SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer
    ): SalesOrderAmendmentTransfer {
        return $salesOrderAmendmentTransfer->fromArray($salesOrderAmendmentRequestTransfer->toArray(), true);
    }
}
