<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinued\Business\Checkout;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class ProductDiscontinuedCheckoutPreConditionChecker implements ProductDiscontinuedCheckoutPreConditionCheckerInterface
{
    protected const PLACE_ORDER_PRE_CHECK_PRODUCT_DISCONTINUED = 'Product %s is discontinued, please choose an alternative one.';

    /**
     * @var \Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedRepositoryInterface
     */
    protected $productDiscontinuedRepository;

    /**
     * @param \Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedRepositoryInterface $productDiscontinuedRepository
     */
    public function __construct($productDiscontinuedRepository)
    {
        $this->productDiscontinuedRepository = $productDiscontinuedRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkCondition(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool
    {
        $isPassed = true;

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$this->isProductDiscontinued($itemTransfer)) {
                continue;
            }

            $this->addDiscontinuedErrorToCheckoutResponse($checkoutResponseTransfer, $itemTransfer->getSku());
            $isPassed = false;
        }

        return $isPassed;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isProductDiscontinued(ItemTransfer $itemTransfer): bool
    {
        return $this->productDiscontinuedRepository->checkIfProductDiscontinuedBySku($itemTransfer->getSku());
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param string $productSku
     *
     * @return void
     */
    protected function addDiscontinuedErrorToCheckoutResponse(
        CheckoutResponseTransfer $checkoutResponseTransfer,
        string $productSku
    ): void {
        $checkoutErrorTransfer = (new CheckoutErrorTransfer())
            ->setMessage(sprintf(static::PLACE_ORDER_PRE_CHECK_PRODUCT_DISCONTINUED, $productSku));

        $checkoutResponseTransfer
            ->addError($checkoutErrorTransfer)
            ->setIsSuccess(false);
    }
}
