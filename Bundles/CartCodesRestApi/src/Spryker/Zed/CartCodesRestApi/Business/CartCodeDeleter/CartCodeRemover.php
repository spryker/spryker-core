<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartCodesRestApi\Business\CartCodeDeleter;

use Generated\Shared\Transfer\CartCodeOperationResultTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $idDiscount
     *
     * @return \Generated\Shared\Transfer\CartCodeOperationResultTransfer
     */
    public function removeCartCode(QuoteTransfer $quoteTransfer, int $idDiscount): CartCodeOperationResultTransfer
    {
        $quoteResponseTransfer = $this->cartsRestApiFacade->findQuoteByUuid($quoteTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->createCartCodeOperationResultTransferWithErrorMessageTransfer(
                CartCodesRestApiConfig::ERROR_IDENTIFIER_CART_NOT_FOUND
            );
        }

        $quoteTransfer = $quoteResponseTransfer->getQuoteTransfer();

        $voucherCode = $this->findVoucherCodeById($quoteTransfer->getVoucherDiscounts()->getArrayCopy(), $idDiscount);
        if (!$voucherCode) {
            return $this->createCartCodeOperationResultTransferWithErrorMessageTransfer(
                CartCodesRestApiConfig::ERROR_IDENTIFIER_CART_CODE_NOT_FOUND
            );
        }

        return $this->cartCodeFacade->removeCode($quoteTransfer, $voucherCode);
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
