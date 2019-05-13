<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequestAgent\Business\Reader;

use Generated\Shared\Transfer\QuoteRequestCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestOverviewCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestOverviewFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Spryker\Zed\QuoteRequestAgent\Dependency\Facade\QuoteRequestAgentToQuoteRequestFacadeInterface;

class QuoteRequestReader implements QuoteRequestReaderInterface
{
    /**
     * @var \Spryker\Zed\QuoteRequestAgent\Dependency\Facade\QuoteRequestAgentToQuoteRequestFacadeInterface
     */
    protected $quoteRequestFacade;

    /**
     * @param \Spryker\Zed\QuoteRequestAgent\Dependency\Facade\QuoteRequestAgentToQuoteRequestFacadeInterface $quoteRequestFacade
     */
    public function __construct(QuoteRequestAgentToQuoteRequestFacadeInterface $quoteRequestFacade)
    {
        $this->quoteRequestFacade = $quoteRequestFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestOverviewFilterTransfer $quoteRequestOverviewFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestOverviewCollectionTransfer
     */
    public function getQuoteRequestOverviewCollection(
        QuoteRequestOverviewFilterTransfer $quoteRequestOverviewFilterTransfer
    ): QuoteRequestOverviewCollectionTransfer {
        return (new QuoteRequestOverviewCollectionTransfer())
            ->setQuoteRequests($this->getQuoteRequestCollection($quoteRequestOverviewFilterTransfer)->getQuoteRequests())
            ->setCurrentQuoteRequest($this->findQuoteRequest($quoteRequestOverviewFilterTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestOverviewFilterTransfer $quoteRequestOverviewFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestCollectionTransfer
     */
    protected function getQuoteRequestCollection(
        QuoteRequestOverviewFilterTransfer $quoteRequestOverviewFilterTransfer
    ): QuoteRequestCollectionTransfer {
        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setWithHidden(true)
            ->setExcludedStatuses($quoteRequestOverviewFilterTransfer->getExcludedStatuses())
            ->setPagination($quoteRequestOverviewFilterTransfer->getPagination());

        return $this->quoteRequestFacade->getQuoteRequestCollectionByFilter($quoteRequestFilterTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestOverviewFilterTransfer $quoteRequestOverviewFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer|null
     */
    protected function findQuoteRequest(QuoteRequestOverviewFilterTransfer $quoteRequestOverviewFilterTransfer): ?QuoteRequestTransfer
    {
        if (!$quoteRequestOverviewFilterTransfer->getQuoteRequestReference()) {
            return null;
        }

        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setQuoteRequestReference($quoteRequestOverviewFilterTransfer->getQuoteRequestReference())
            ->setWithHidden(true);

        return $this->quoteRequestFacade
            ->getQuoteRequest($quoteRequestFilterTransfer)
            ->getQuoteRequest();
    }
}
