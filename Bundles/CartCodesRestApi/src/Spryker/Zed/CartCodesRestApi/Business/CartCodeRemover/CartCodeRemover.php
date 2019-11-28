<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartCodesRestApi\Business\CartCodeRemover;

use Generated\Shared\Transfer\CartCodeOperationResultTransfer;
use Generated\Shared\Transfer\CartCodeRequestTransfer;
use Generated\Shared\Transfer\CartCodeResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Shared\CartCodesRestApi\CartCodesRestApiConfig;
use Spryker\Zed\CartCodesRestApi\Dependency\Facade\CartCodesRestApiToCartCodeFacadeInterface;
use Spryker\Zed\CartCodesRestApi\Dependency\Facade\CartCodesRestApiToCartsRestApiFacadeInterface;

class CartCodeRemover implements CartCodeRemoverInterface
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
    public function removeCartCode(CartCodeRequestTransfer $cartCodeRequestTransfer): CartCodeResponseTransfer
    {
        $quoteTransfer = $cartCodeRequestTransfer->getQuote();
        $quoteResponseTransfer = $this->cartsRestApiFacade->findQuoteByUuid($quoteTransfer);

//        if (!$quoteResponseTransfer->getIsSuccessful()) {
//            return $this->createCartCodeOperationResultTransferWithErrorMessageTransfer(
//                CartCodesRestApiConfig::ERROR_IDENTIFIER_CART_NOT_FOUND
//            );
//        }
//
//        if (!$cartCode) {
//            return $this->createCartCodeOperationResultTransferWithErrorMessageTransfer(
//                CartCodesRestApiConfig::ERROR_IDENTIFIER_CART_CODE_NOT_FOUND
//            );
//        }

        $cartCodeRequestTransfer->setQuote($quoteResponseTransfer->getQuoteTransfer());

        return $this->cartCodeFacade->removeCartCode($cartCodeRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer[] $discountTransfers
     * @param int $idDiscount
     *
     * @return string|null
     */
    protected function findVoucherCodeById(array $discountTransfers, int $idDiscount): ?string
    {
        foreach ($discountTransfers as $discountTransfer) {
            if ($discountTransfer->getIdDiscount() === $idDiscount) {
                return $discountTransfer->getVoucherCode();
            }
        }

        return null;
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
