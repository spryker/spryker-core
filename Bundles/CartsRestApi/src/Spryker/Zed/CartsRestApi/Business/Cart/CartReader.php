<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\Cart;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToQuoteFacadeInterface;

class CartReader implements CartReaderInterface
{
    /**
     * @var \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @param \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToQuoteFacadeInterface $quoteFacade
     */
    public function __construct(CartsRestApiToQuoteFacadeInterface $quoteFacade)
    {
        $this->quoteFacade = $quoteFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function findCustomerQuoteByUuid(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $customerReference = $quoteTransfer->getCustomerReference();

        $quoteResponseTransfer = $this->quoteFacade->findQuoteByUuid($quoteTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return (new QuoteResponseTransfer())->setIsSuccessful(false);
        }

        if ($quoteResponseTransfer->getQuoteTransfer()->getCustomerReference() !== $customerReference) {
            return (new QuoteResponseTransfer())->setIsSuccessful(false);
        }

        return $quoteResponseTransfer;
    }
}
