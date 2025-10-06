<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySalesConnector\Business\Expander;

use Exception;
use Generated\Shared\Transfer\OrderFilterTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CompanySalesConnector\Business\Checker\EditCompanyOrdersPermissionCheckerInterface;
use Spryker\Zed\CompanySalesConnector\Dependency\Facade\CompanySalesConnectorToSalesFacadeInterface;

class EditCompanyOrderQuoteExpander implements EditCompanyOrderQuoteExpanderInterface
{
    /**
     * @param \Spryker\Zed\CompanySalesConnector\Business\Checker\EditCompanyOrdersPermissionCheckerInterface $editCompanyOrdersPermissionChecker
     * @param \Spryker\Zed\CompanySalesConnector\Dependency\Facade\CompanySalesConnectorToSalesFacadeInterface $salesFacade
     */
    public function __construct(
        protected EditCompanyOrdersPermissionCheckerInterface $editCompanyOrdersPermissionChecker,
        protected CompanySalesConnectorToSalesFacadeInterface $salesFacade
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

        if (!$this->editCompanyOrdersPermissionChecker->isEditCompanyOrderAllowed($companyUserTransfer)) {
            return $quoteTransfer;
        }

        $orderTransfer = $this->findOrder($quoteTransfer);

        if (!$this->editCompanyOrdersPermissionChecker->isOrderBelongsToCompany($orderTransfer, $companyUserTransfer)) {
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
