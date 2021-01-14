<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequestsRestApi\Business\Creator;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestsRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\QuoteRequestsRestApi\Business\Reader\QuoteReaderInterface;
use Spryker\Zed\QuoteRequestsRestApi\Dependency\Facade\QuoteRequestsRestApiToQuoteRequestFacadeInterface;

class QuoteRequestCreator implements QuoteRequestCreatorInterface
{
    /**
     * @var \Spryker\Zed\QuoteRequestsRestApi\Business\Reader\QuoteReaderInterface
     */
    protected $quoteReader;

    /**
     * @var \Spryker\Zed\QuoteRequestsRestApi\Dependency\Facade\QuoteRequestsRestApiToQuoteRequestFacadeInterface
     */
    protected $quoteRequestFacade;

    /**
     * @param \Spryker\Zed\QuoteRequestsRestApi\Business\Reader\QuoteReaderInterface $quoteReader
     * @param \Spryker\Zed\QuoteRequestsRestApi\Dependency\Facade\QuoteRequestsRestApiToQuoteRequestFacadeInterface $quoteRequestFacade
     */
    public function __construct(
        QuoteReaderInterface $quoteReader,
        QuoteRequestsRestApiToQuoteRequestFacadeInterface $quoteRequestFacade
    ) {
        $this->quoteReader = $quoteReader;
        $this->quoteRequestFacade = $quoteRequestFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestsRequestTransfer $quoteRequestsRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function createQuoteRequest(
        QuoteRequestsRequestTransfer $quoteRequestsRequestTransfer
    ): QuoteRequestResponseTransfer {
        $quoteResponseTransfer = $this->quoteReader->findQuoteByUuidForCustomer(
            $quoteRequestsRequestTransfer->getCustomerOrFail(),
            $quoteRequestsRequestTransfer->getCartUuidOrFail()
        );

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            $quoteRequestResponseTransfer = (new QuoteRequestResponseTransfer())
                ->setIsSuccessful(false);

            return $this->processErrorMessagesFromQuoteResponse(
                $quoteResponseTransfer,
                $quoteRequestResponseTransfer
            );
        }

        $quoteRequestTransfer = $this->buildQuoteRequestTransfer(
            $quoteRequestsRequestTransfer,
            $quoteResponseTransfer->getQuoteTransfer()
        );

        return $this->quoteRequestFacade->createQuoteRequest($quoteRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     * @param \Generated\Shared\Transfer\QuoteRequestResponseTransfer $quoteRequestResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    protected function processErrorMessagesFromQuoteResponse(
        QuoteResponseTransfer $quoteResponseTransfer,
        QuoteRequestResponseTransfer $quoteRequestResponseTransfer
    ): QuoteRequestResponseTransfer {
        foreach ($quoteResponseTransfer->getErrors() as $quoteErrorTransfer) {
            $messageTransfer = (new MessageTransfer())->setMessage($quoteErrorTransfer->getErrorIdentifier());
            $quoteRequestResponseTransfer->addMessage($messageTransfer);
        }

        return $quoteRequestResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestsRequestTransfer $quoteRequestsRequestTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    protected function buildQuoteRequestTransfer(
        QuoteRequestsRequestTransfer $quoteRequestsRequestTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteRequestTransfer {
        $quoteRequestTransfer = new QuoteRequestTransfer();
        $customerTransfer = $quoteRequestsRequestTransfer->getCustomerOrFail();
        $quoteRequestTransfer->setCompanyUser($customerTransfer->getCompanyUserTransfer());
        $quoteRequestVersionTransfer = new QuoteRequestVersionTransfer();
        $quoteRequestVersionTransfer->setMetadata($quoteRequestsRequestTransfer->getMeta());
        $quoteRequestVersionTransfer->setQuote($quoteTransfer);
        $quoteRequestTransfer->setLatestVersion($quoteRequestVersionTransfer);

        return $quoteRequestTransfer;
    }
}
