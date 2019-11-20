<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartCodesRestApi\Business\CartCodeAdder;

use Generated\Shared\Transfer\CartCodeOperationResultTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\CartCodesRestApi\CartCodesRestApiConfig;
use Spryker\Zed\CartCodesRestApi\Dependency\Facade\CartCodesRestApiToCartCodeFacadeInterface;
use Spryker\Zed\CartCodesRestApi\Dependency\Facade\CartCodesRestApiToCartsRestApiFacadeInterface;

class CartCodeAdder implements CartCodeAdderInterface
{
    protected const MESSAGE_TYPE_ERROR = 'error';

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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $voucherCode
     *
     * @return \Generated\Shared\Transfer\CartCodeOperationResultTransfer
     */
    public function addCandidate(QuoteTransfer $quoteTransfer, string $voucherCode): CartCodeOperationResultTransfer
    {
        $quoteResponseTransfer = $this->cartsRestApiFacade->findQuoteByUuid($quoteTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->createCartCodeOperationResultTransferWithErrorMessageTransfer(
                CartCodesRestApiConfig::ERROR_IDENTIFIER_CART_NOT_FOUND
            );
        }

        $cartCodeOperationResultTransfer = $this->cartCodeFacade->addCandidate($quoteResponseTransfer->getQuoteTransfer(), $voucherCode);

        foreach ($cartCodeOperationResultTransfer->getMessages() as $messageTransfer) {
            if ($messageTransfer->getType() !== static::MESSAGE_TYPE_ERROR) {
                continue;
            }

            return $this->createCartCodeOperationResultTransferWithErrorMessageTransfer(
                CartCodesRestApiConfig::ERROR_IDENTIFIER_CART_CODE_CANT_BE_ADDED
            );
        }

        return $cartCodeOperationResultTransfer;
    }

    /**
     * @param string $errorIdentifier
     *
     * @return \Generated\Shared\Transfer\CartCodeOperationResultTransfer
     */
    protected function createCartCodeOperationResultTransferWithErrorMessageTransfer(string $errorIdentifier): CartCodeOperationResultTransfer
    {
        return (new CartCodeOperationResultTransfer())->addMessage(
            (new MessageTransfer())->setValue($errorIdentifier)
        );
    }
}
