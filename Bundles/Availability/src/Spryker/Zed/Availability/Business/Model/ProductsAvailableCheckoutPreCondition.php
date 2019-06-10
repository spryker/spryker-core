<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Availability\AvailabilityConfig;
use Spryker\Zed\Availability\Dependency\Service\AvailabilityToUtilQuantityServiceInterface;

class ProductsAvailableCheckoutPreCondition implements ProductsAvailableCheckoutPreConditionInterface
{
    protected const CHECKOUT_PRODUCT_UNAVAILABLE_TRANSLATION_KEY = 'product.unavailable';

    /**
     * @var \Spryker\Zed\Availability\Business\Model\SellableInterface
     */
    protected $sellable;

    /**
     * @var \Spryker\Zed\Availability\AvailabilityConfig
     */
    protected $availabilityConfig;

    /**
     * @var \Spryker\Zed\Availability\Dependency\Service\AvailabilityToUtilQuantityServiceInterface
     */
    protected $utilQuantityService;

    /**
     * @param \Spryker\Zed\Availability\Business\Model\SellableInterface $sellable
     * @param \Spryker\Zed\Availability\AvailabilityConfig $availabilityConfig
     * @param \Spryker\Zed\Availability\Dependency\Service\AvailabilityToUtilQuantityServiceInterface $utilQuantityService
     */
    public function __construct(
        SellableInterface $sellable,
        AvailabilityConfig $availabilityConfig,
        AvailabilityToUtilQuantityServiceInterface $utilQuantityService
    ) {
        $this->sellable = $sellable;
        $this->availabilityConfig = $availabilityConfig;
        $this->utilQuantityService = $utilQuantityService;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return bool
     */
    public function checkCondition(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $quoteTransfer->requireStore();

        $isPassed = true;

        $storeTransfer = $quoteTransfer->getStore();
        $groupedItemQuantities = $this->groupItemsBySku($quoteTransfer->getItems());

        foreach ($groupedItemQuantities as $sku => $quantity) {
            if ($this->isProductSellable($sku, $quantity, $storeTransfer) === true) {
                continue;
            }
            $this->addAvailabilityErrorToCheckoutResponse($checkoutResponse, $sku);
            $isPassed = false;
        }

        return $isPassed;
    }

    /**
     * @param string $sku
     * @param int $quantity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    protected function isProductSellable($sku, $quantity, StoreTransfer $storeTransfer)
    {
        return $this->sellable->isProductSellableForStore($sku, $quantity, $storeTransfer);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return array
     */
    private function groupItemsBySku(ArrayObject $items)
    {
        $result = [];

        foreach ($items as $itemTransfer) {
            $sku = $itemTransfer->getSku();

            if (!isset($result[$sku])) {
                $result[$sku] = 0;
            }
            $result[$sku] = $this->sumQuantities(
                $result[$sku],
                $itemTransfer->getQuantity()
            );
        }

        return $result;
    }

    /**
     * @param float $firstQuantity
     * @param float $secondQuantity
     *
     * @return float
     */
    protected function sumQuantities(float $firstQuantity, float $secondQuantity): float
    {
        return $this->utilQuantityService->sumQuantities($firstQuantity, $secondQuantity);
    }

    /**
     * @return \Generated\Shared\Transfer\CheckoutErrorTransfer
     */
    protected function createCheckoutErrorTransfer()
    {
        return new CheckoutErrorTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     * @param string $sku
     *
     * @return void
     */
    protected function addAvailabilityErrorToCheckoutResponse(CheckoutResponseTransfer $checkoutResponse, string $sku)
    {
        $checkoutErrorTransfer = $this->createCheckoutErrorTransfer();
        $checkoutErrorTransfer
            ->setErrorCode($this->availabilityConfig->getProductUnavailableErrorCode())
            ->setMessage(static::CHECKOUT_PRODUCT_UNAVAILABLE_TRANSLATION_KEY)
            ->setErrorType(
                $this->availabilityConfig->getAvailabilityErrorType()
            )
            ->setParameters([
                $this->availabilityConfig->getAvailabilityProductSkuParameter() => $sku,
            ]);

        $checkoutResponse
            ->addError($checkoutErrorTransfer)
            ->setIsSuccess(false);
    }
}
