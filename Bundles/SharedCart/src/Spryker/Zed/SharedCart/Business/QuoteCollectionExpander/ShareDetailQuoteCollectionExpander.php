<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Business\QuoteCollectionExpander;

use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Spryker\Zed\SharedCart\Business\QuoteShareDetails\QuoteShareDetailsReaderInterface;

class ShareDetailQuoteCollectionExpander implements ShareDetailQuoteCollectionExpanderInterface
{
    /**
     * @var \Spryker\Zed\SharedCart\Business\QuoteShareDetails\QuoteShareDetailsReaderInterface
     */
    protected $quoteShareDetailsReader;

    /**
     * @param \Spryker\Zed\SharedCart\Business\QuoteShareDetails\QuoteShareDetailsReaderInterface $quoteShareDetailsReader
     */
    public function __construct(QuoteShareDetailsReaderInterface $quoteShareDetailsReader)
    {
        $this->quoteShareDetailsReader = $quoteShareDetailsReader;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteCollectionTransfer $quoteCollectionTransfer
     * @param \Generated\Shared\Transfer\QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function expandQuoteCollectionWithCustomerShareDetail(
        QuoteCollectionTransfer $quoteCollectionTransfer,
        QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer
    ): QuoteCollectionTransfer {
        if (!$quoteCriteriaFilterTransfer->getIdCompanyUser()) {
            return $quoteCollectionTransfer;
        }

        $resultingQuoteCollectionTransfer = new QuoteCollectionTransfer();
        foreach ($quoteCollectionTransfer->getQuotes() as $quoteTransfer) {
            $shareDetailCollectionTransfer = $this->quoteShareDetailsReader
                ->getShareDetailsByIdQuote($quoteTransfer);

            foreach ($shareDetailCollectionTransfer->getShareDetails() as $shareDetailTransfer) {
                if ($quoteCriteriaFilterTransfer->getIdCompanyUser() === $shareDetailTransfer->getIdCompanyUser()) {
                    $quoteTransfer->addShareDetail($shareDetailTransfer);
                }
            }

            $resultingQuoteCollectionTransfer->addQuote($quoteTransfer);
        }

        return $resultingQuoteCollectionTransfer;
    }
}
