<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade;

use Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionResponseTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteCriteriaTransfer;

class SalesOrderAmendmentOmsToSalesOrderAmendmentFacadeBridge implements SalesOrderAmendmentOmsToSalesOrderAmendmentFacadeInterface
{
    /**
     * @var \Spryker\Zed\SalesOrderAmendment\Business\SalesOrderAmendmentFacadeInterface
     */
    protected $salesOrderAmendmentFacade;

    /**
     * @param \Spryker\Zed\SalesOrderAmendment\Business\SalesOrderAmendmentFacadeInterface $salesOrderAmendmentFacade
     */
    public function __construct($salesOrderAmendmentFacade)
    {
        $this->salesOrderAmendmentFacade = $salesOrderAmendmentFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentQuoteCriteriaTransfer $salesOrderAmendmentQuoteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionTransfer
     */
    public function getSalesOrderAmendmentQuoteCollection(
        SalesOrderAmendmentQuoteCriteriaTransfer $salesOrderAmendmentQuoteCriteriaTransfer
    ): SalesOrderAmendmentQuoteCollectionTransfer {
        return $this->salesOrderAmendmentFacade->getSalesOrderAmendmentQuoteCollection($salesOrderAmendmentQuoteCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionDeleteCriteriaTransfer $salesOrderAmendmentQuoteCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionResponseTransfer
     */
    public function deleteSalesOrderAmendmentQuoteCollection(
        SalesOrderAmendmentQuoteCollectionDeleteCriteriaTransfer $salesOrderAmendmentQuoteCollectionDeleteCriteriaTransfer
    ): SalesOrderAmendmentQuoteCollectionResponseTransfer {
        return $this->salesOrderAmendmentFacade
            ->deleteSalesOrderAmendmentQuoteCollection($salesOrderAmendmentQuoteCollectionDeleteCriteriaTransfer);
    }
}
