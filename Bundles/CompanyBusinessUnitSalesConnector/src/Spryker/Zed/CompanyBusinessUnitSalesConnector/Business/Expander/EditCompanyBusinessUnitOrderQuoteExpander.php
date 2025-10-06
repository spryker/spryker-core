<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Expander;

use Exception;
use Generated\Shared\Transfer\OrderFilterTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Checker\EditCompanyBusinessUnitOrdersPermissionCheckerInterface;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Dependency\Facade\CompanyBusinessUnitSalesConnectorToSalesFacadeInterface;

class EditCompanyBusinessUnitOrderQuoteExpander implements EditCompanyBusinessUnitOrderQuoteExpanderInterface
{
    /**
     * @param \Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Checker\EditCompanyBusinessUnitOrdersPermissionCheckerInterface $editCompanyBusinessUnitOrdersPermissionChecker
     * @param \Spryker\Zed\CompanyBusinessUnitSalesConnector\Dependency\Facade\CompanyBusinessUnitSalesConnectorToSalesFacadeInterface $salesFacade
     */
    public function __construct(
        protected EditCompanyBusinessUnitOrdersPermissionCheckerInterface $editCompanyBusinessUnitOrdersPermissionChecker,
        protected CompanyBusinessUnitSalesConnectorToSalesFacadeInterface $salesFacade
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuoteWithOriginalOrder(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        if ($quoteTransfer->getOriginalOrder()) {
            return $quoteTransfer;
        }

        $companyUserTransfer = $quoteTransfer->getCustomerOrFail()->getCompanyUserTransfer();

        if (!$companyUserTransfer) {
            return $quoteTransfer;
        }

        if (!$this->editCompanyBusinessUnitOrdersPermissionChecker->isEditCompanyBusinessUnitOrderAllowed($companyUserTransfer)) {
            return $quoteTransfer;
        }

        $orderTransfer = $this->findOrder($quoteTransfer);

        if (
            !$this->editCompanyBusinessUnitOrdersPermissionChecker->isOrderBelongsToCompanyBusinessUnit(
                $orderTransfer,
                $companyUserTransfer,
            )
        ) {
            return $quoteTransfer;
        }

        $quoteTransfer->setOriginalOrder($orderTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer|null
     */
    protected function findOrder(QuoteTransfer $quoteTransfer): ?OrderTransfer
    {
        $orderFilterTransfer = (new OrderFilterTransfer())
            ->setOrderReference($quoteTransfer->getAmendmentOrderReferenceOrFail())
            ->setWithUniqueProductCount(false);

        try {
            return $this->salesFacade->getOrder($orderFilterTransfer);
        } catch (Exception $e) {
            return null;
        }
    }
}
