<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\Availability\PreCheck;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;

class ProductPackagingUnitCartPreCheck extends ProductPackagingUnitAvailabilityPreCheck implements ProductPackagingUnitCartPreCheckInterface
{
    public const CART_PRE_CHECK_ITEM_AVAILABILITY_LEAD_PRODUCT_FAILED = 'cart.pre.check.availability.failed.lead.product';
    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function checkCartAvailability(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        $cartErrorMessages = new ArrayObject();
        $this->assertQuote($cartChangeTransfer);
        $storeTransfer = $cartChangeTransfer->getQuote()->getStore();
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getAmountLeadProduct() || !$itemTransfer->getAmount()) {
                continue;
            }

            $this->assertAmountPackagingUnitExpanded($itemTransfer);

            // No need for checking if packaging unit is sellable
            // it's already checked in the product pre-check

            if ($itemTransfer->getAmount() > 0) { // It hasLeadProduct
                if (!$this->isPackagingUnitLeadProductSellable(
                    $itemTransfer,
                    $cartChangeTransfer->getItems(),
                    $storeTransfer
                )) {
                    $cartErrorMessages[] = $this->createMessageTransfer(
                        static::CART_PRE_CHECK_ITEM_AVAILABILITY_LEAD_PRODUCT_FAILED,
                        ['sku' => $itemTransfer->getSku()]
                    );
                }
            }
        }

        return $this->createCartPreCheckResponseTransfer($cartErrorMessages);
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return void
     */
    protected function assertQuote(CartChangeTransfer $cartChangeTransfer)
    {
        $cartChangeTransfer->requireQuote();

        $cartChangeTransfer->getQuote()->requireStore();
    }

    /**
     * @param string $message
     * @param array|null $params
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMessageTransfer(string $message, array $params = []): MessageTransfer
    {
        return (new MessageTransfer())
            ->setValue($message)
            ->setParameters($params);
    }

    /**
     * @param \ArrayObject $cartErrorMessages
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    protected function createCartPreCheckResponseTransfer(ArrayObject $cartErrorMessages)
    {
        return (new CartPreCheckResponseTransfer())
            ->setIsSuccess(count($cartErrorMessages) === 0)
            ->setMessages($cartErrorMessages);
    }
}
