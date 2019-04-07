<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Business\QuoteRequest;

use Generated\Shared\Transfer\QuoteRequestCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionFilterTransfer;
use Spryker\Zed\QuoteRequest\Persistence\QuoteRequestRepositoryInterface;

class QuoteRequestReader implements QuoteRequestReaderInterface
{
    /**
     * @var \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestRepositoryInterface
     */
    protected $quoteRequestRepository;

    /**
     * @param \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestRepositoryInterface $quoteRequestRepository
     */
    public function __construct(QuoteRequestRepositoryInterface $quoteRequestRepository)
    {
        $this->quoteRequestRepository = $quoteRequestRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestCollectionTransfer
     */
    public function getQuoteRequestCollectionByFilter(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestCollectionTransfer
    {
        $quoteRequestCollectionTransfer = $this->quoteRequestRepository
            ->getQuoteRequestCollectionByFilter($quoteRequestFilterTransfer);

        $quoteRequestCollectionTransfer = $this->expandQuoteRequestCollectionWithVersions($quoteRequestCollectionTransfer);

        return $quoteRequestCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestVersionFilterTransfer $quoteRequestVersionFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionCollectionTransfer
     */
    public function getQuoteRequestVersionCollectionByFilter(QuoteRequestVersionFilterTransfer $quoteRequestVersionFilterTransfer): QuoteRequestVersionCollectionTransfer
    {
        return $this->quoteRequestRepository
            ->getQuoteRequestVersionCollectionByFilter($quoteRequestVersionFilterTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestCollectionTransfer $quoteRequestCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestCollectionTransfer
     */
    protected function expandQuoteRequestCollectionWithVersions(
        QuoteRequestCollectionTransfer $quoteRequestCollectionTransfer
    ): QuoteRequestCollectionTransfer {
        foreach ($quoteRequestCollectionTransfer->getQuoteRequests() as $quoteRequestTransfer) {
            $quoteRequestVersionFilterTransfer = (new QuoteRequestVersionFilterTransfer())
                ->setQuoteRequest($quoteRequestTransfer);

            $quoteRequestVersionTransfers = $this->quoteRequestRepository
                ->getQuoteRequestVersionCollectionByFilter($quoteRequestVersionFilterTransfer)
                ->getQuoteRequestVersions();

            $quoteRequestTransfer->setLatestVersion($quoteRequestVersionTransfers->offsetGet(0));

            if ($quoteRequestTransfer->getIsLatestVersionHidden()) {
                if ($quoteRequestVersionTransfers->offsetExists(1)) {
                    $quoteRequestTransfer->setLatestVisibleVersion($quoteRequestVersionTransfers->offsetGet(1));
                }

                continue;
            }

            $quoteRequestTransfer->setLatestVisibleVersion($quoteRequestTransfer->getLatestVersion());
        }

        return $quoteRequestCollectionTransfer;
    }
}
