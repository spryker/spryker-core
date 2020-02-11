<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartCodesRestApi\Dependency\Facade;

use Generated\Shared\Transfer\CartCodeRequestTransfer;
use Generated\Shared\Transfer\CartCodeResponseTransfer;

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
     * @param \Generated\Shared\Transfer\CartCodeRequestTransfer $cartCodeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartCodeResponseTransfer
     */
    public function addCartCode(CartCodeRequestTransfer $cartCodeRequestTransfer): CartCodeResponseTransfer
    {
        return $this->cartCodeFacade->addCartCode($cartCodeRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CartCodeRequestTransfer $cartCodeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartCodeResponseTransfer
     */
    public function removeCartCode(CartCodeRequestTransfer $cartCodeRequestTransfer): CartCodeResponseTransfer
    {
        return $this->cartCodeFacade->removeCartCode($cartCodeRequestTransfer);
    }
}
