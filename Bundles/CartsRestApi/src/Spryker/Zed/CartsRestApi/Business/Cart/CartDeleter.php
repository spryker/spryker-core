<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\Cart;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\RestQuoteRequestTransfer;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToPersistentCartFacadeInterface;

class CartDeleter implements CartDeleterInterface
{
    /**
     * @var \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToPersistentCartFacadeInterface
     */
    protected $persistentCartFacade;

    /**
     * @param \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToPersistentCartFacadeInterface $persistentCartFacade
     */
    public function __construct(CartsRestApiToPersistentCartFacadeInterface $persistentCartFacade)
    {
        $this->persistentCartFacade = $persistentCartFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\RestQuoteRequestTransfer $restQuoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function deleteQuote(RestQuoteRequestTransfer $restQuoteRequestTransfer): QuoteResponseTransfer
    {
        $restQuoteRequestTransfer
            ->requireQuoteUuid()
            ->requireCustomerReference();
        $quoteTransfer = $restQuoteRequestTransfer->getQuote();

        return $this->persistentCartFacade->delete($quoteTransfer);
    }
}
