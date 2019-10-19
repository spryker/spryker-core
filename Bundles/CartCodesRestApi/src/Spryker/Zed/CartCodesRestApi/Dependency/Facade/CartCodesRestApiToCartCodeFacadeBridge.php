<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartCodesRestApi\Dependency\Facade;

use Generated\Shared\Transfer\CartCodeOperationResultTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class CartCodesRestApiToCartCodeFacadeBridge implements CartCodesRestApiToCartCodeFacadeInterface
{
    /**
     * @var \Spryker\Zed\CartCode\Business\CartCodeFacadeInterface
     */
    protected $cartCodeFacade;

    /**
     * @param \Spryker\Zed\CartCode\Business\CartCodeFacadeInterface $cartCodeFacade
     */
    public function __construct($cartCodeFacade)
    {
        $this->cartCodeFacade = $cartCodeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\CartCodeOperationResultTransfer
     */
    public function addCandidate(QuoteTransfer $quoteTransfer, string $code): CartCodeOperationResultTransfer
    {
        return $this->cartCodeFacade->addCandidate($quoteTransfer, $code);
    }
}
