<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCartsRestApi\Business\SharedCart;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShareDetailCollectionTransfer;
use Spryker\Zed\Quote\Business\QuoteFacadeInterface;
use Spryker\Zed\SharedCart\Business\SharedCartFacadeInterface;

class SharedCartReader implements SharedCartReaderInterface
{
    /**
     * @var \Spryker\Zed\Quote\Business\QuoteFacadeInterface
     */
    private $quoteFacade;

    /**
     * @var \Spryker\Zed\SharedCart\Business\SharedCartFacadeInterface
     */
    private $sharedCartFacade;

    /**
     * @param \Spryker\Zed\Quote\Business\QuoteFacadeInterface $quoteFacade
     * @param \Spryker\Zed\SharedCart\Business\SharedCartFacadeInterface $sharedCartFacade
     */
    public function __construct(QuoteFacadeInterface $quoteFacade, SharedCartFacadeInterface $sharedCartFacade)
    {
        $this->quoteFacade = $quoteFacade;
        $this->sharedCartFacade = $sharedCartFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShareDetailCollectionTransfer
     */
    public function getSharedCartsByCartUuid(QuoteTransfer $quoteTransfer): ShareDetailCollectionTransfer
    {
        $quoteResponseTransfer = $this->quoteFacade->findQuoteByUuid($quoteTransfer);

        return $this->sharedCartFacade->getShareDetailsByIdQuote($quoteResponseTransfer->getQuoteTransfer());
    }
}
