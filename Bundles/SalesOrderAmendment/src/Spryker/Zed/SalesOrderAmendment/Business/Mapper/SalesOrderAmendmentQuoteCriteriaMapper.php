<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Business\Mapper;

use Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteConditionsTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteCriteriaTransfer;

class SalesOrderAmendmentQuoteCriteriaMapper implements SalesOrderAmendmentQuoteCriteriaMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionDeleteCriteriaTransfer $salesOrderAmendmentQuoteCollectionDeleteCriteriaTransfer
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentQuoteCriteriaTransfer $salesOrderAmendmentQuoteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentQuoteCriteriaTransfer
     */
    public function mapSalesOrderAmendmentQuoteCollectionDeleteCriteriaTransferToSalesOrderAmendmentQuoteCriteriaTransfer(
        SalesOrderAmendmentQuoteCollectionDeleteCriteriaTransfer $salesOrderAmendmentQuoteCollectionDeleteCriteriaTransfer,
        SalesOrderAmendmentQuoteCriteriaTransfer $salesOrderAmendmentQuoteCriteriaTransfer
    ): SalesOrderAmendmentQuoteCriteriaTransfer {
        $salesOrderAmendmentQuoteConditionsTransfer = $salesOrderAmendmentQuoteCriteriaTransfer->getSalesOrderAmendmentQuoteConditions()
            ?? new SalesOrderAmendmentQuoteConditionsTransfer();
        $salesOrderAmendmentQuoteConditionsTransfer->fromArray($salesOrderAmendmentQuoteCollectionDeleteCriteriaTransfer->modifiedToArray(), true);

        return $salesOrderAmendmentQuoteCriteriaTransfer->setSalesOrderAmendmentQuoteConditions($salesOrderAmendmentQuoteConditionsTransfer);
    }
}
