<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartCodesRestApi\Business\CartCodeAdder;

use Generated\Shared\Transfer\CartCodeRequestTransfer;
use Generated\Shared\Transfer\CartCodeResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Shared\CartCodesRestApi\CartCodesRestApiConfig;
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
    protected $cartsRestApiFacade;

    /**
     * @param \Spryker\Zed\CartCodesRestApi\Dependency\Facade\CartCodesRestApiToCartCodeFacadeInterface $cartCodeFacade
     * @param \Spryker\Zed\CartCodesRestApi\Dependency\Facade\CartCodesRestApiToCartsRestApiFacadeInterface $cartsRestApiFacade
     */
    public function __construct(
        CartCodesRestApiToCartCodeFacadeInterface $cartCodeFacade,
        CartCodesRestApiToCartsRestApiFacadeInterface $cartsRestApiFacade
    ) {
        $this->cartCodeFacade = $cartCodeFacade;
        $this->cartsRestApiFacade = $cartsRestApiFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CartCodeRequestTransfer $cartCodeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartCodeResponseTransfer
     */
    public function addCartCode(CartCodeRequestTransfer $cartCodeRequestTransfer): CartCodeResponseTransfer
    {
        $quoteResponseTransfer = $this->cartsRestApiFacade->findQuoteByUuid($cartCodeRequestTransfer->getQuote());

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->createCartCodeResponseTransferWithErrorMessageTransfer(
                CartCodesRestApiConfig::ERROR_IDENTIFIER_CART_NOT_FOUND
            );
        }

        $cartCodeRequestTransfer->setQuote($quoteResponseTransfer->getQuoteTransfer());
        $cartCodeResponseTransfer = $this->cartCodeFacade->addCartCode($cartCodeRequestTransfer);

        if (!$cartCodeResponseTransfer->getIsSuccessful()) {
            return $this->createCartCodeResponseTransferWithErrorMessageTransfer(
                CartCodesRestApiConfig::ERROR_IDENTIFIER_CART_CODE_CANT_BE_ADDED
            );
        }

        return $cartCodeResponseTransfer;
    }

    /**
     * @param string $errorIdentifier
     *
     * @return \Generated\Shared\Transfer\CartCodeResponseTransfer
     */
    protected function createCartCodeResponseTransferWithErrorMessageTransfer(string $errorIdentifier): CartCodeResponseTransfer
    {
        return (new CartCodeResponseTransfer())
            ->setIsSuccessful(false)
            ->addMessage((new MessageTransfer())->setValue($errorIdentifier));
    }
}
