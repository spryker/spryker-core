<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequestAgentsRestApi\Business\Updater;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Zed\QuoteRequestAgentsRestApi\Business\Reader\QuoteReaderInterface;
use Spryker\Zed\QuoteRequestAgentsRestApi\Dependency\Facade\QuoteRequestAgentsRestApiToQuoteRequestAgentFacadeInterface;

class QuoteRequestUpdater implements QuoteRequestUpdaterInterface
{
    /**
     * @var \Spryker\Zed\QuoteRequestAgentsRestApi\Business\Reader\QuoteReaderInterface
     */
    protected $quoteReader;

    /**
     * @var \Spryker\Zed\QuoteRequestAgentsRestApi\Dependency\Facade\QuoteRequestAgentsRestApiToQuoteRequestAgentFacadeInterface
     */
    protected $quoteRequestFacade;

    /**
     * @param \Spryker\Zed\QuoteRequestAgentsRestApi\Business\Reader\QuoteReaderInterface $quoteReader
     * @param \Spryker\Zed\QuoteRequestAgentsRestApi\Dependency\Facade\QuoteRequestAgentsRestApiToQuoteRequestAgentFacadeInterface $quoteRequestFacade
     */
    public function __construct(
        QuoteReaderInterface $quoteReader,
        QuoteRequestAgentsRestApiToQuoteRequestAgentFacadeInterface $quoteRequestFacade
    ) {
        $this->quoteReader = $quoteReader;
        $this->quoteRequestFacade = $quoteRequestFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function updateQuoteRequest(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer
    {
        $quoteTransfer = $quoteRequestTransfer->getLatestVersionOrFail()->getQuoteOrFail();
        $quoteResponseTransfer = $this->quoteReader->findQuoteByUuidForCustomer(
            $quoteRequestTransfer->getCompanyUserOrFail()->getCustomerOrFail(),
            $quoteTransfer->getUuidOrFail(),
        );

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            $quoteRequestResponseTransfer = (new QuoteRequestResponseTransfer())
                ->setIsSuccessful(false);

            return $this->processErrorMessagesFromQuoteResponse(
                $quoteResponseTransfer,
                $quoteRequestResponseTransfer,
            );
        }

        $quoteRequestTransfer->getLatestVersionOrFail()->setQuote($quoteResponseTransfer->getQuoteTransfer());

        return $this->quoteRequestFacade->updateQuoteRequest($quoteRequestTransfer);
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
            $messageTransfer = (new MessageTransfer())->setValue($quoteErrorTransfer->getErrorIdentifier());
            $quoteRequestResponseTransfer->addMessage($messageTransfer);
        }

        return $quoteRequestResponseTransfer;
    }
}
