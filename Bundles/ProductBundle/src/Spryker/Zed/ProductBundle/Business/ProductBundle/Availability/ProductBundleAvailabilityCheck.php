<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Availability;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityInterface;
use Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToAvailabilityQueryContainerInterface;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface;
use \ArrayObject;

class ProductBundleAvailabilityCheck implements ProductBundleAvailabilityCheckInterface
{

    const CART_PRE_CHECK_ITEM_AVAILABILITY_FAILED = 'cart.pre.check.availability.failed';
    const CHECKOUT_PRODUCT_UNAVAILABLE_TRANSLATION_KEY = 'product.unavailable';
    const CART_PRE_CHECK_ITEM_AVAILABILITY_EMPTY = 'cart.pre.check.availability.failed.empty';
    const STOCK_TRANSLATION_PARAMETER = 'stock';
    const SKU_TRANSLATION_PARAMTER = 'sku';

    /**
     * @var \Spryker\Zed\AvailabilityCartConnector\Dependency\Facade\AvailabilityCartConnectorToAvailabilityInterface
     */
    protected $availabilityFacade;

    /**
     * @var \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface
     */
    protected $productBundleQueryContainer;

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
        $this->availabilityFacade = $availabilityFacade;
        $this->productBundleQueryContainer = $productBundleQueryContainer;
        $this->availabilityQueryContainer = $availabilityQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function checkCheckoutAvailability(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $itemsInCart = $quoteTransfer->getItems();

        $checkoutErrorMessages = new ArrayObject();
        $uniqueBundleItems = $this->getUniqueBundleItems($quoteTransfer);

        foreach ($uniqueBundleItems as $bundleItemTransfer) {
            $bundledItems = $this->findBundledProducts($bundleItemTransfer->getSku());
            if (!$this->isAllCheckoutBundledItemsAvailable($itemsInCart, $bundledItems)) {
                $checkoutErrorMessages[] = $this->createCheckoutResponseTransfer();
            }
        }

        if (count($checkoutErrorMessages) > 0) {
            $checkoutResponseTransfer->setIsSuccess(false);

            foreach ($checkoutErrorMessages as $checkoutErrorTransfer) {
                $checkoutResponseTransfer->addError($checkoutErrorTransfer);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function checkCartAvailability(CartChangeTransfer $cartChangeTransfer)
    {
        $cartPreCheckErrorMessages = new ArrayObject();
        $itemsInCart = $cartChangeTransfer->getQuote()->getItems();
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {

            $bundledItems = $this->findBundledProducts($itemTransfer->getSku());

            if (count($bundledItems) > 0) {
                if (!$this->isAllBundleItemsAvailable($itemsInCart, $bundledItems, $itemTransfer->getQuantity())) {
                    $availabilityEntity = $this->findAvailabilityEntityBySku($itemTransfer->getSku());

                    $cartPreCheckErrorMessages[] = $this->createItemIsNotAvailableMessageTransfer(
                        $availabilityEntity->getQuantity(),
                        $itemTransfer->getSku()
                    );
                }
                continue;
            }

            $sku = $itemTransfer->getSku();
            $itemQuantity = $itemTransfer->getQuantity();

            if (!$this->checkIfItemIsSellable($itemsInCart, $sku, $itemQuantity)) {
                $bundleAvailability = $this->availabilityFacade->calculateStockForProduct($sku);

                $bundledItemsQuantity = $this->getAccumulatedItemQuantityForBundledProductsByGivenSku(
                    $itemsInCart,
                    $itemTransfer->getSku()
                );

                $availabilityAfterBundling = $bundleAvailability - $bundledItemsQuantity;

                $cartPreCheckErrorMessages->append(
                    $this->createItemIsNotAvailableMessageTransfer(
                        $availabilityAfterBundling,
                        $itemTransfer->getSku()
                    )
                );
            }

        }

        return $this->createCartPreCheckResponseTransfer($cartPreCheckErrorMessages);
    }

    /**
     * @param \ArrayObject $items
     * @param string $sku
     *
     * @return int
     */
    protected function getAccumulatedItemQuantityForGivenSku(ArrayObject $items, $sku)
    {
        $quantity = 0;
        foreach ($items as $itemTransfer) {
            if ($itemTransfer->getSku() !== $sku) {
                continue;
            }
            $quantity += $itemTransfer->getQuantity();
        }

        return $quantity;
    }

    /**
     * @param \ArrayObject $items
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
     * @param string $stock
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
     * @return \Generated\Shared\Transfer\CheckoutErrorTransfer
     */
    protected function createCheckoutResponseTransfer()
    {
        $checkoutErrorTransfer = new CheckoutErrorTransfer();
        $checkoutErrorTransfer->setMessage(static::CHECKOUT_PRODUCT_UNAVAILABLE_TRANSLATION_KEY);

        return $checkoutErrorTransfer;
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
     * @param \ArrayObject $items
     * @param string $sku
     * @param int $itemQuantity
     *
     * @return bool
     */
    protected function checkIfItemIsSellable(ArrayObject $items, $sku, $itemQuantity = 0)
    {
        $currentItemQuantity = $this->getAccumulatedItemQuantityForGivenSku($items, $sku);
        $currentItemQuantity += $itemQuantity;

        return $this->availabilityFacade->isProductSellable($sku, $currentItemQuantity);
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
        $messageTranfer = new MessageTransfer();
        $messageTranfer->setValue($translationKey);
        $messageTranfer->setParameters([
            static::SKU_TRANSLATION_PARAMTER => $sku,
            static::STOCK_TRANSLATION_PARAMETER => $stock,
        ]);

        return $messageTranfer;
    }

    /**
     * @param \ArrayObject $messages
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
     * @param \ArrayObject $quoteItems
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ProductBundle\Persistence\Base\SpyProductBundle[] $bundledProducts
     * @param int $cartItemQuantity
     *
     * @return bool
     */
    protected function isAllBundleItemsAvailable(ArrayObject $quoteItems, ObjectCollection $bundledProducts, $cartItemQuantity)
    {
        foreach ($bundledProducts as $productBundleEntity) {
            $bundledProductConcreteEntity = $productBundleEntity->getSpyProductRelatedByFkBundledProduct();

            $sku = $bundledProductConcreteEntity->getSku();
            $totalBundledItemQuantity = $productBundleEntity->getQuantity() * $cartItemQuantity;
            if (!$this->checkIfItemIsSellable($quoteItems, $sku, $totalBundledItemQuantity)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \ArrayObject $currentCartItems
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ProductBundle\Persistence\Base\SpyProductBundle[] $bundledItems
     *
     * @return bool
     */
    protected function isAllCheckoutBundledItemsAvailable(ArrayObject $currentCartItems, ObjectCollection $bundledItems)
    {
        foreach ($bundledItems as $productBundleEntity) {
            $bundledProductConcreteEntity = $productBundleEntity->getSpyProductRelatedByFkBundledProduct();

            $sku = $bundledProductConcreteEntity->getSku();
            if (!$this->checkIfItemIsSellable($currentCartItems, $sku)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function getUniqueBundleItems(QuoteTransfer $quoteTransfer)
    {
        $uniqueBundledItems = [];
        foreach ($quoteTransfer->getBundleItems() as $bundleItemTransfer) {
            if (!isset($uniqueBundledItems[$bundleItemTransfer->getSku()])) {
                $uniqueBundledItems[$bundleItemTransfer->getSku()] = $bundleItemTransfer;
                continue;
            }
        }

        return $uniqueBundledItems;
    }

    /**
     * @param string $sku
     *
     * @return \Orm\Zed\ProductBundle\Persistence\SpyProductBundle[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function findBundledProducts($sku)
    {
        return $this->productBundleQueryContainer
            ->queryBundleProductBySku($sku)
            ->find();
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

}
