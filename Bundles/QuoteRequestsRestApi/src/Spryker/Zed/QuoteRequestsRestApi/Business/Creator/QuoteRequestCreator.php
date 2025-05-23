<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequestsRestApi\Business\Creator;

use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Spryker\Zed\QuoteRequestsRestApi\Business\Mapper\QuoteRequestResponseMapperInterface;
use Spryker\Zed\QuoteRequestsRestApi\Business\Reader\QuoteReaderInterface;
use Spryker\Zed\QuoteRequestsRestApi\Dependency\Facade\QuoteRequestsRestApiToQuoteRequestFacadeInterface;

class QuoteRequestCreator implements QuoteRequestCreatorInterface
{
    /**
     * @param \Spryker\Zed\QuoteRequestsRestApi\Business\Reader\QuoteReaderInterface $quoteReader
     * @param \Spryker\Zed\QuoteRequestsRestApi\Business\Mapper\QuoteRequestResponseMapperInterface $quoteRequestResponseMapper
     * @param \Spryker\Zed\QuoteRequestsRestApi\Dependency\Facade\QuoteRequestsRestApiToQuoteRequestFacadeInterface $quoteRequestFacade
     */
    public function __construct(
        protected QuoteReaderInterface $quoteReader,
        protected QuoteRequestResponseMapperInterface $quoteRequestResponseMapper,
        protected QuoteRequestsRestApiToQuoteRequestFacadeInterface $quoteRequestFacade
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function createQuoteRequest(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer
    {
        $quoteTransfer = $quoteRequestTransfer->getLatestVersionOrFail()->getQuoteOrFail();
        $quoteResponseTransfer = $this->quoteReader->findQuoteByUuidForCustomer(
            $quoteTransfer->getCustomerOrFail(),
            $quoteTransfer->getUuidOrFail(),
        );

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            $quoteRequestResponseTransfer = (new QuoteRequestResponseTransfer())
                ->setIsSuccessful(false);

            return $this->quoteRequestResponseMapper->mapErrorMessagesFromQuoteResponseToQuoteRequestResponse(
                $quoteResponseTransfer,
                $quoteRequestResponseTransfer,
            );
        }

        $quoteRequestTransfer->getLatestVersionOrFail()->setQuote($quoteResponseTransfer->getQuoteTransfer());
        if ($quoteResponseTransfer->getQuoteTransfer() !== null && $quoteResponseTransfer->getQuoteTransfer()->getCustomer() !== null) {
            $quoteRequestTransfer->setCompanyUser($quoteResponseTransfer->getQuoteTransfer()->getCustomer()->getCompanyUserTransfer());
        }

        $quoteRequestResponseTransfer = $this->quoteRequestFacade->isQuoteApplicableForQuoteRequest($quoteRequestTransfer);

        if (!$quoteRequestResponseTransfer->getIsSuccessful()) {
            return $quoteRequestResponseTransfer;
        }

        return $this->quoteRequestFacade->createQuoteRequest($quoteRequestTransfer);
    }
}
