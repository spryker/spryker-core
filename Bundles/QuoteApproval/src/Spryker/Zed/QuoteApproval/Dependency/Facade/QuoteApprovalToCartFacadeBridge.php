<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Dependency\Facade;

use Generated\Shared\Transfer\QuoteTransfer;

class QuoteApprovalToCartFacadeBridge implements QuoteApprovalToCartFacadeInterface
{
    /**
     * @var \Spryker\Zed\Cart\Business\CartFacadeInterface
     */
    protected $cartFacade;

    /**
     * @param \Spryker\Zed\Cart\Business\CartFacadeInterface $cartFacade
     */
    public function __construct($cartFacade)
    {
        $this->cartFacade = $cartFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function lockQuote(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->cartFacade->lockQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function unlockQuote(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->cartFacade->unlockQuote($quoteTransfer);
    }
}
