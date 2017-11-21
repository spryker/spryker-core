<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\PreCheck;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityInterface;
use Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToAvailabilityQueryContainerInterface;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface;

class ProductBundleCartAvailabilityCheck extends BasePreCheck implements ProductBundleCartAvailabilityCheckInterface
{
    const CART_PRE_CHECK_ITEM_AVAILABILITY_FAILED = 'cart.pre.check.availability.failed';
    const CART_PRE_CHECK_ITEM_AVAILABILITY_EMPTY = 'cart.pre.check.availability.failed.empty';
    const STOCK_TRANSLATION_PARAMETER = 'stock';
    const SKU_TRANSLATION_PARAMTER = 'sku';

    /**
     * @var \Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToAvailabilityQueryContainerInterface
     */
    protected $availabilityQueryContainer;

    /**
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityInterface $availabilityFacade
     * @param \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface $productBundleQueryContainer
     * @param \Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToAvailabilityQueryContainerInterface $availabilityQueryContainer
     */
    public function __construct(
        ProductBundleToAvailabilityInterface $availabilityFacade,
        ProductBundleQueryContainerInterface $productBundleQueryContainer,
        ProductBundleToAvailabilityQueryContainerInterface $availabilityQueryContainer
    ) {
        parent::__construct($availabilityFacade, $productBundleQueryContainer);

        $this->availabilityQueryContainer = $availabilityQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function checkCartAvailability(CartChangeTransfer $cartChangeTransfer)
    {
        $cartPreCheckFailedItems = new ArrayObject();
        $itemsInCart = $cartChangeTransfer->getQuote()->getItems();
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $itemTransfer->requireSku()->requireQuantity();

            $messageTransfer = $this->checkItemAvailability($itemsInCart, $itemTransfer);

            if ($messageTransfer !== null) {
                $cartPreCheckFailedItems[] = $messageTransfer;
            }
        }

        return $this->createCartPreCheckResponseTransfer($cartPreCheckFailedItems);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     * @param string $sku
     *
     * @return int
     */
    protected function getAccumulatedItemQuantityForBundledProductsByGivenSku(ArrayObject $items, $sku)
    {
        $quantity = 0;
        foreach ($items as $itemTransfer) {
            if (!$itemTransfer->getRelatedBundleItemIdentifier()) {
                continue;
            }

            if ($itemTransfer->getSku() !== $sku) {
                continue;
            }
            $quantity += $itemTransfer->getQuantity();
        }

        return $quantity;
    }

    /**
     * @param int $stock
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createItemIsNotAvailableMessageTransfer($stock, $sku)
    {
        $translationKey = $this->getItemAvailabilityTranslationKey($stock);
        return $this->createCartMessageTransfer($stock, $translationKey, $sku);
    }

    /**
     * @param int $stock
     * @param string $translationKey
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createCartMessageTransfer($stock, $translationKey, $sku)
    {
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue($translationKey);
        $messageTransfer->setParameters([
            static::SKU_TRANSLATION_PARAMTER => $sku,
            static::STOCK_TRANSLATION_PARAMETER => $stock,
        ]);

        return $messageTransfer;
    }

    /**
     * @param int $stock
     *
     * @return string
     */
    protected function getItemAvailabilityTranslationKey($stock)
    {
        $translationKey = static::CART_PRE_CHECK_ITEM_AVAILABILITY_FAILED;
        if ($stock <= 0) {
            $translationKey = static::CART_PRE_CHECK_ITEM_AVAILABILITY_EMPTY;
        }
        return $translationKey;
    }

    /**
     * @param string $sku
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailability
     */
    protected function findAvailabilityEntityBySku($sku)
    {
        return $this->availabilityQueryContainer
            ->querySpyAvailabilityBySku($sku)
            ->findOne();
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\MessageTransfer[] $messages
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    protected function createCartPreCheckResponseTransfer(ArrayObject $messages)
    {
        $cartPreCheckResponseTransfer = new CartPreCheckResponseTransfer();
        $cartPreCheckResponseTransfer->setIsSuccess(count($messages) == 0);
        $cartPreCheckResponseTransfer->setMessages($messages);

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemsInCart
     *
     * @return int
     */
    protected function calculateRegularItemAvailability(ItemTransfer $itemTransfer, ArrayObject $itemsInCart)
    {
        $itemAvailability = $this->availabilityFacade->calculateStockForProduct($itemTransfer->getSku());

        $bundledItemsQuantity = $this->getAccumulatedItemQuantityForBundledProductsByGivenSku(
            $itemsInCart,
            $itemTransfer->getSku()
        );

        $availabilityAfterBundling = $itemAvailability - $bundledItemsQuantity;

        return $availabilityAfterBundling;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemsInCart
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\MessageTransfer|null
     */
    protected function checkItemAvailability(ArrayObject $itemsInCart, ItemTransfer $itemTransfer)
    {
        $bundledProducts = $this->findBundledProducts($itemTransfer->getSku());

        if (count($bundledProducts) > 0) {
            $messageTransfer = $this->checkBundleAvailability($itemsInCart, $bundledProducts, $itemTransfer);
        } else {
            $messageTransfer = $this->checkRegularItemAvailability($itemsInCart, $itemTransfer);
        }

        return $messageTransfer;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemsInCart
     * @param mixed|mixed[]|\Orm\Zed\ProductBundle\Persistence\SpyProductBundle[]|\Propel\Runtime\ActiveRecord\ActiveRecordInterface[]|\Propel\Runtime\Collection\ObjectCollection $bundledProducts
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\MessageTransfer|null
     */
    protected function checkBundleAvailability(
        ArrayObject $itemsInCart,
        ObjectCollection $bundledProducts,
        ItemTransfer $itemTransfer
    ) {
        if ($this->isAllBundleItemsAvailable($itemsInCart, $bundledProducts, $itemTransfer->getQuantity())) {
            return null;
        }

        $availabilityEntity = $this->findAvailabilityEntityBySku($itemTransfer->getSku());

        return $this->createItemIsNotAvailableMessageTransfer(
            $availabilityEntity->getQuantity(),
            $itemTransfer->getSku()
        );
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemsInCart
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\MessageTransfer|null
     */
    protected function checkRegularItemAvailability($itemsInCart, $itemTransfer)
    {
        if ($this->checkIfItemIsSellable($itemsInCart, $itemTransfer->getSku(), $itemTransfer->getQuantity())) {
            return null;
        }

        $availability = $this->calculateRegularItemAvailability($itemTransfer, $itemsInCart);
        return $this->createItemIsNotAvailableMessageTransfer($availability, $itemTransfer->getSku());
    }
}
