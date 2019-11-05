<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartCodesRestApi\Business\CartCodeDeleter;

use Generated\Shared\Transfer\CartCodeOperationResultTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartCodesRestApi\Dependency\Facade\CartCodesRestApiToCartCodeFacadeInterface;
use Spryker\Zed\CartCodesRestApi\Dependency\Facade\CartCodesRestApiToCartsRestApiFacadeInterface;

class CartCodeDeleter implements CartCodeDeleterInterface
{
    /**
     * @uses \Spryker\Shared\CartsRestApi\CartsRestApiConfig::ERROR_IDENTIFIER_CART_NOT_FOUND
     */
    protected const ERROR_IDENTIFIER_CART_NOT_FOUND = 'ERROR_IDENTIFIER_CART_NOT_FOUND';

    protected const ERROR_MESSAGE_INVALID_DISCOUNT_ID = 'Invalid discount id.';

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
     * @param int $idDiscount
     *
     * @return \Generated\Shared\Transfer\CartCodeOperationResultTransfer
     */
    public function removeCode(QuoteTransfer $quoteTransfer, int $idDiscount): CartCodeOperationResultTransfer
    {
        $quoteResponseTransfer = $this->cartsRestApiFacade->findQuoteByUuid($quoteTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->createCartCodeOperationResultTransferWithCartNotFoundErrorMessageTransfer();
        }

        $quoteTransfer = $quoteResponseTransfer->getQuoteTransfer();
        /** @var \Generated\Shared\Transfer\DiscountTransfer[] $discountTransfers */
        $discountTransfers = array_merge(
            $quoteTransfer->getVoucherDiscounts()->getArrayCopy(),
            $quoteTransfer->getCartRuleDiscounts()->getArrayCopy()
        );

        $voucherCode = '';
        foreach ($discountTransfers as $discountTransfer) {
            if ($discountTransfer->getIdDiscount() === $idDiscount) {
                $voucherCode = $discountTransfer->getVoucherCode();
            }
        }

        if (!$voucherCode) {
            return $this->createCartCodeOperationResultTransferWithInvalidDiscountIdErrorMessageTransfer();
        }

        return $this->cartCodeFacade->removeCode($quoteTransfer, $voucherCode);
    }

    /**
     * @return \Generated\Shared\Transfer\CartCodeOperationResultTransfer
     */
    protected function createCartCodeOperationResultTransferWithCartNotFoundErrorMessageTransfer(): CartCodeOperationResultTransfer
    {
        return (new CartCodeOperationResultTransfer())->addMessage(
            (new MessageTransfer())->setValue(static::ERROR_IDENTIFIER_CART_NOT_FOUND)
        );
    }

    /**
     * @return \Generated\Shared\Transfer\CartCodeOperationResultTransfer
     */
    protected function createCartCodeOperationResultTransferWithInvalidDiscountIdErrorMessageTransfer(): CartCodeOperationResultTransfer
    {
        return (new CartCodeOperationResultTransfer())->addMessage(
            (new MessageTransfer())->setValue(static::ERROR_MESSAGE_INVALID_DISCOUNT_ID)
        );
    }
}
