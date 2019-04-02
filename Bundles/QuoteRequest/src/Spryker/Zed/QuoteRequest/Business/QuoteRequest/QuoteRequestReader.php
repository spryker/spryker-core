<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Business\QuoteRequest;

use Generated\Shared\Transfer\QuoteRequestCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
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

        $quoteRequestCollectionTransfer = $this->expandQuoteRequestWithLatestVisibleVersion($quoteRequestCollectionTransfer);

        return $quoteRequestCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestCollectionTransfer $quoteRequestCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestCollectionTransfer
     */
    protected function expandQuoteRequestWithLatestVisibleVersion(
        QuoteRequestCollectionTransfer $quoteRequestCollectionTransfer
    ): QuoteRequestCollectionTransfer {
        foreach ($quoteRequestCollectionTransfer->getQuoteRequests() as $quoteRequestTransfer) {
            $latestVisibleVersion = $quoteRequestTransfer->getLatestVersion();

            if ($quoteRequestTransfer->getIsLatestVersionHidden()) {
                $latestVisibleVersion = $this->quoteRequestRepository
                    ->findQuoteRequestLatestVisibleVersion($quoteRequestTransfer->getIdQuoteRequest());
            }

            $quoteRequestTransfer->setLatestVisibleVersion($latestVisibleVersion);
        }

        return $quoteRequestCollectionTransfer;
    }
}
