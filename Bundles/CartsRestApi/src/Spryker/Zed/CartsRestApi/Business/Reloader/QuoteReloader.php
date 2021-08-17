<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\Reloader;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartsRestApi\CartsRestApiConfig;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToCartFacadeInterface;

class QuoteReloader implements QuoteReloaderInterface
{
    /**
     * @var \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToCartFacadeInterface
     */
    protected $cartFacade;

    /**
     * @var \Spryker\Zed\CartsRestApi\CartsRestApiConfig
     */
    protected $cartRestApiConfig;

    /**
     * @param \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToCartFacadeInterface $cartFacade
     * @param \Spryker\Zed\CartsRestApi\CartsRestApiConfig $cartRestApiConfig
     */
    public function __construct(
        CartsRestApiToCartFacadeInterface $cartFacade,
        CartsRestApiConfig $cartRestApiConfig
    ) {
        $this->cartFacade = $cartFacade;
        $this->cartRestApiConfig = $cartRestApiConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function reloadQuoteItems(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        if (!$this->cartRestApiConfig->isQuoteReloadEnabled()) {
            return $quoteTransfer;
        }

        $quoteTransfer->requireCustomer();

        return $this->cartFacade->reloadItems($quoteTransfer);
    }
}
