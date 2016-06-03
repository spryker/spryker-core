<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Business\Model;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Checkout\CheckoutConstants;

class ProductsAvailableCheckoutPreCondition
{

    /**
     * @var \Spryker\Zed\Availability\Business\Model\SellableInterface
     */
    protected $sellable;

    /**
     * @param \Spryker\Zed\Availability\Business\Model\SellableInterface $sellable
     */
    public function __construct(SellableInterface $sellable)
    {
        $this->sellable = $sellable;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function checkCondition(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $groupedItemQuantities = $this->groupItemsBySku($quoteTransfer->getItems());

        foreach ($groupedItemQuantities as $sku => $quantity) {
            if (!$this->isProductSellable($sku, $quantity)) {
                $checkoutErrorTransfer = $this->createCheckoutErrorTransfer();
                $checkoutErrorTransfer
                    ->setErrorCode(CheckoutConstants::ERROR_CODE_PRODUCT_UNAVAILABLE)
                    ->setMessage('product.unavailable');

                $checkoutResponse
                    ->addError($checkoutErrorTransfer)
                    ->setIsSuccess(false);
            }
        }
    }

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return bool
     */
    protected function isProductSellable($sku, $quantity)
    {
        return $this->sellable->isProductSellable($sku, $quantity);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return array
     */
    private function groupItemsBySku(\ArrayObject $items)
    {
        $result = [];

        foreach ($items as $item) {
            $sku = $item->getSku();

            if (!isset($result[$sku])) {
                $result[$sku] = 0;
            }
            $result[$sku] += $item->getQuantity();
        }

        return $result;
    }

    /**
     * @return \Generated\Shared\Transfer\CheckoutErrorTransfer
     */
    protected function createCheckoutErrorTransfer()
    {
        return new CheckoutErrorTransfer();
    }

}
