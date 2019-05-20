<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Business\QuoteCollectionExpander;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
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
    public function __construct(
        QuoteShareDetailsReaderInterface $quoteShareDetailsReader
    ) {
        $this->quoteShareDetailsReader = $quoteShareDetailsReader;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\QuoteCollectionTransfer $quoteCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function expandQuoteCollectionWithCustomerShareDetail(
        CustomerTransfer $customerTransfer,
        QuoteCollectionTransfer $quoteCollectionTransfer
    ): QuoteCollectionTransfer {
        if (!$customerTransfer->getCompanyUserTransfer()->getIdCompanyUser()) {
            return $quoteCollectionTransfer;
        }

        foreach ($quoteCollectionTransfer->getQuotes() as $quoteIndex => $quoteTransfer) {
            $shareDetailCollectionTransfer = $this->quoteShareDetailsReader->getShareDetailsByIdQuote($quoteTransfer);

            foreach ($shareDetailCollectionTransfer->getShareDetails() as $shareDetailTransfer) {
                if ($customerTransfer->getCompanyUserTransfer()->getIdCompanyUser() === $shareDetailTransfer->getIdCompanyUser()) {
                    $quoteCollectionTransfer->offsetSet($quoteIndex, $quoteTransfer->addShareDetail($shareDetailTransfer));
                }
            }
        }

        return $quoteCollectionTransfer;
    }
}
