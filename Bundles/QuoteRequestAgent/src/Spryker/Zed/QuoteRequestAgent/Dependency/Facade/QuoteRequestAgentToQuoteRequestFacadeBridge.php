<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequestAgent\Dependency\Facade;

use Generated\Shared\Transfer\QuoteRequestCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestCriteriaTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;

class QuoteRequestAgentToQuoteRequestFacadeBridge implements QuoteRequestAgentToQuoteRequestFacadeInterface
{
    /**
     * @var \Spryker\Zed\QuoteRequest\Business\QuoteRequestFacadeInterface
     */
    protected $quoteRequestFacade;

    /**
     * @param \Spryker\Zed\QuoteRequest\Business\QuoteRequestFacadeInterface $quoteRequestFacade
     */
    public function __construct($quoteRequestFacade)
    {
        $this->quoteRequestFacade = $quoteRequestFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function createUserQuoteRequest(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer
    {
        return $this->quoteRequestFacade->createUserQuoteRequest($quoteRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function updateUserQuoteRequest(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer
    {
        return $this->quoteRequestFacade->updateUserQuoteRequest($quoteRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function cancelUserQuoteRequest(QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer): QuoteRequestResponseTransfer
    {
        return $this->quoteRequestFacade->cancelUserQuoteRequest($quoteRequestCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function reviseUserQuoteRequest(QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer): QuoteRequestResponseTransfer
    {
        return $this->quoteRequestFacade->reviseUserQuoteRequest($quoteRequestCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function sendQuoteRequestToCustomer(QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer): QuoteRequestResponseTransfer
    {
        return $this->quoteRequestFacade->sendQuoteRequestToCustomer($quoteRequestCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestCollectionTransfer
     */
    public function getQuoteRequestCollectionByFilter(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestCollectionTransfer
    {
        return $this->quoteRequestFacade->getQuoteRequestCollectionByFilter($quoteRequestFilterTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer|null
     */
    public function findQuoteRequest(QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer): ?QuoteRequestTransfer
    {
        return $this->quoteRequestFacade->findQuoteRequest($quoteRequestCriteriaTransfer);
    }
}
