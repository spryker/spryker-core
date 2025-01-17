<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrderAmendmentsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestOrderAmendmentsAttributesTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentTransfer;

interface OrderAmendmentsMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer
     * @param \Generated\Shared\Transfer\RestOrderAmendmentsAttributesTransfer $restOrderAmendmentsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestOrderAmendmentsAttributesTransfer
     */
    public function mapSalesOrderAmendmentTransferToRestOrderAmendmentsAttributesTransfer(
        SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer,
        RestOrderAmendmentsAttributesTransfer $restOrderAmendmentsAttributesTransfer
    ): RestOrderAmendmentsAttributesTransfer;
}
