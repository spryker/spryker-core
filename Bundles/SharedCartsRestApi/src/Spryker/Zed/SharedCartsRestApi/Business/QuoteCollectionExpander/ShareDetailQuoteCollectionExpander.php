<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCartsRestApi\Business\QuoteCollectionExpander;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Spryker\Zed\SharedCartsRestApi\Dependency\Facade\SharedCartsRestApiToSharedCartFacadeInterface;

class ShareDetailQuoteCollectionExpander implements ShareDetailQuoteCollectionExpanderInterface
{
    /**
     * @var \Spryker\Zed\SharedCartsRestApi\Dependency\Facade\SharedCartsRestApiToSharedCartFacadeInterface
     */
    protected $sharedCartFacade;

    /**
     * @param \Spryker\Zed\SharedCartsRestApi\Dependency\Facade\SharedCartsRestApiToSharedCartFacadeInterface $sharedCartFacade
     */
    public function __construct(
        SharedCartsRestApiToSharedCartFacadeInterface $sharedCartFacade
    ) {
        $this->sharedCartFacade = $sharedCartFacade;
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
            $shareDetailCollectionTransfer = $this->sharedCartFacade->getShareDetailsByIdQuote($quoteTransfer);

            foreach ($shareDetailCollectionTransfer->getShareDetails() as $shareDetailTransfer) {
                if ($customerTransfer->getCompanyUserTransfer()->getIdCompanyUser() === $shareDetailTransfer->getIdCompanyUser()) {
                    $quoteCollectionTransfer->offsetSet($quoteIndex, $quoteTransfer->addShareDetail($shareDetailTransfer));
                }
            }
        }

        return $quoteCollectionTransfer;
    }
}
