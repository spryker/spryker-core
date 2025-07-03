<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Checkout\Dependency\Facade;

class CheckoutToQuoteFacadeAdapter implements CheckoutToQuoteFacadeInterface
{
    /**
     * @var \Spryker\Zed\Quote\Business\QuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @param \Spryker\Zed\Quote\Business\QuoteFacadeInterface $quoteFacade
     */
    public function __construct($quoteFacade)
    {
        $this->quoteFacade = $quoteFacade;
    }

    /**
     * @param int $idQuote
     *
     * @return bool
     */
    public function acquireExclusiveLockForQuote(int $idQuote): bool
    {
        /* Exists for backwards compatibility because `spryker/quote`:"^1.0.0" does not have `acquireExclusiveLockForQuote` but the version is supported. */
        if (method_exists($this->quoteFacade, 'acquireExclusiveLockForQuote') === false) {
            return true;
        }

        return $this->quoteFacade->acquireExclusiveLockForQuote($idQuote);
    }
}
