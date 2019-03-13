<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentQuoteRequest\Communication\Controller;

use Generated\Shared\Transfer\CompanyUserAutocompleteResponseTransfer;
use Generated\Shared\Transfer\CompanyUserQueryTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestOverviewCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestOverviewFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\AgentQuoteRequest\Business\AgentQuoteRequestFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\QuoteRequestOverviewFilterTransfer $quoteRequestOverviewFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestOverviewCollectionTransfer
     */
    public function getQuoteRequestOverviewCollectionAction(
        QuoteRequestOverviewFilterTransfer $quoteRequestOverviewFilterTransfer
    ): QuoteRequestOverviewCollectionTransfer {
        return $this->getFacade()
            ->getQuoteRequestOverviewCollection($quoteRequestOverviewFilterTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function cancelByReferenceAction(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestResponseTransfer
    {
        return $this->getFacade()->cancelByReference($quoteRequestFilterTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function setQuoteRequestEditableAction(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestResponseTransfer
    {
        return $this->getFacade()->setQuoteRequestEditable($quoteRequestFilterTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserQueryTransfer $customerQueryTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserAutocompleteResponseTransfer
     */
    public function findCompanyUsersByQueryAction(CompanyUserQueryTransfer $customerQueryTransfer): CompanyUserAutocompleteResponseTransfer
    {
        return $this->getFacade()->findCompanyUsersByQuery($customerQueryTransfer);
    }
}
