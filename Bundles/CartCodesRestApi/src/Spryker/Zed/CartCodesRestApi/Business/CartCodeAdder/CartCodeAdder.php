<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartCodesRestApi\Business\CartCodeAdder;

use Generated\Shared\Transfer\CartCodeOperationResultTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartCodesRestApi\Dependency\Facade\CartCodesRestApiToCartCodeFacadeInterface;
use Spryker\Zed\CartCodesRestApi\Dependency\Facade\CartCodesRestApiToCartsRestApiFacadeInterface;

class CartCodeAdder implements CartCodeAdderInterface
{
    /**
     * @var \Spryker\Zed\CartCodesRestApi\Dependency\Facade\CartCodesRestApiToCartCodeFacadeInterface
     */
    protected $cartCodeFacade;

    /**
     * @var \Spryker\Zed\CartCodesRestApi\Dependency\Facade\CartCodesRestApiToCartsRestApiFacadeInterface
     */
    protected $cartsRestApi;

    /**
     * @param \Spryker\Zed\CartCodesRestApi\Dependency\Facade\CartCodesRestApiToCartCodeFacadeInterface $cartCodeFacade
     * @param \Spryker\Zed\CartCodesRestApi\Dependency\Facade\CartCodesRestApiToCartsRestApiFacadeInterface $cartsRestApi
     */
    public function __construct(
        CartCodesRestApiToCartCodeFacadeInterface $cartCodeFacade,
        CartCodesRestApiToCartsRestApiFacadeInterface $cartsRestApi
    ) {
        $this->cartCodeFacade = $cartCodeFacade;
        $this->cartsRestApi = $cartsRestApi;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $voucherCode
     *
     * @return \Generated\Shared\Transfer\CartCodeOperationResultTransfer
     */
    public function addCandidate(QuoteTransfer $quoteTransfer, string $voucherCode): CartCodeOperationResultTransfer
    {
        $quoteTransfer = $this->cartsRestApi->findQuoteByUuid($quoteTransfer)->getQuoteTransfer();

        return $this->cartCodeFacade->addCandidate($quoteTransfer, $voucherCode);
    }
}
