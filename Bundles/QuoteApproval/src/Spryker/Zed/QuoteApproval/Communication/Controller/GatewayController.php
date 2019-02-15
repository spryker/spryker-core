<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Communication\Controller;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\QuoteApprovalRequestTransfer;
use Generated\Shared\Transfer\QuoteApprovalResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\QuoteApproval\Business\QuoteApprovalFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function createQuoteApprovalAction(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer): QuoteApprovalResponseTransfer
    {
        return $this->getFacade()->createQuoteApproval($quoteApprovalRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function removeQuoteApprovalAction(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer): QuoteApprovalResponseTransfer
    {
        return $this->getFacade()->removeQuoteApproval($quoteApprovalRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function getQuoteApproverListAction(QuoteTransfer $quoteTransfer): CompanyUserCollectionTransfer
    {
        return $this->getFacade()->getQuoteApproverList($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function approveQuoteApprovalAction(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer): QuoteApprovalResponseTransfer
    {
        return $this->getFacade()->approveQuoteApproval($quoteApprovalRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function declineQuoteApprovalAction(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer): QuoteApprovalResponseTransfer
    {
        return $this->getFacade()->declineQuoteApproval($quoteApprovalRequestTransfer);
    }
}
