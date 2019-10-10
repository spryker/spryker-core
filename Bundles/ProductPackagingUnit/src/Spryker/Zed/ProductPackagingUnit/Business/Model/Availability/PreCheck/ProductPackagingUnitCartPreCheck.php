<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\Availability\PreCheck;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToAvailabilityFacadeInterface;
use Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface;

class ProductPackagingUnitCartPreCheck extends ProductPackagingUnitAvailabilityPreCheck implements ProductPackagingUnitCartPreCheckInterface
{
    public const CART_PRE_CHECK_ITEM_AVAILABILITY_LEAD_PRODUCT_FAILED = 'cart.pre.check.availability.failed.lead.product';

    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface
     */
    protected $productPackagingUnitRepository;

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface $productPackagingUnitRepository
     * @param \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToAvailabilityFacadeInterface $availabilityFacade
     */
    public function __construct(
        ProductPackagingUnitRepositoryInterface $productPackagingUnitRepository,
        ProductPackagingUnitToAvailabilityFacadeInterface $availabilityFacade
    ) {
        $this->productPackagingUnitRepository = $productPackagingUnitRepository;
        parent::__construct($availabilityFacade);
    }

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
        $cartItems = clone $cartChangeTransfer->getItems();
        foreach ($cartItems as $itemTransfer) {
            if (!$itemTransfer->getAmount()) {
                continue;
            }

            $this->expandItemWithLeadProduct($itemTransfer);

            $isPackagingUnitLeadProductSellable = $this->isPackagingUnitLeadProductSellable(
                $itemTransfer,
                $cartItems,
                $storeTransfer
            );

            if ($itemTransfer->getAmount()->greaterThan(0) && !$isPackagingUnitLeadProductSellable) {
                $cartErrorMessages[] = $this->createMessageTransfer(
                    static::CART_PRE_CHECK_ITEM_AVAILABILITY_LEAD_PRODUCT_FAILED,
                    ['sku' => $itemTransfer->getSku()]
                );
            }
        }

        return $this->createCartPreCheckResponseTransfer($cartErrorMessages);
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return void
     */
    protected function assertQuote(CartChangeTransfer $cartChangeTransfer): void
    {
        $cartChangeTransfer->requireQuote();

        $cartChangeTransfer->getQuote()->requireStore();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function expandItemWithLeadProduct(ItemTransfer $itemTransfer)
    {
        $itemTransfer->requireSku();
        $productPackagingLeadProductTransfer = $this->productPackagingUnitRepository
            ->findProductPackagingLeadProductBySiblingProductSku($itemTransfer->getSku());

        if ($productPackagingLeadProductTransfer) {
            $itemTransfer->setAmountLeadProduct($productPackagingLeadProductTransfer);
        }

        return $itemTransfer;
    }

    /**
     * @param string $message
     * @param array|null $params
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMessageTransfer(string $message, ?array $params = []): MessageTransfer
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
    protected function createCartPreCheckResponseTransfer(ArrayObject $cartErrorMessages): CartPreCheckResponseTransfer
    {
        return (new CartPreCheckResponseTransfer())
            ->setIsSuccess(count($cartErrorMessages) === 0)
            ->setMessages($cartErrorMessages);
    }
}
