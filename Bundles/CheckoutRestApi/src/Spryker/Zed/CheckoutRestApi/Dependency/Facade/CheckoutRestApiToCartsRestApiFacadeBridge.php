<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CheckoutRestApi\Dependency\Facade;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class CheckoutRestApiToCartsRestApiFacadeBridge implements CheckoutRestApiToCartsRestApiFacadeInterface
{
    /**
     * @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacadeInterface
     */
    protected $cartsRestApiFacade;

    /**
     * @param \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacadeInterface $cartsRestApiFacade
     */
    public function __construct($cartsRestApiFacade)
    {
        $this->cartsRestApiFacade = $cartsRestApiFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function findQuoteByUuid(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->cartsRestApiFacade->findQuoteByUuid($quoteTransfer);
    }
}
