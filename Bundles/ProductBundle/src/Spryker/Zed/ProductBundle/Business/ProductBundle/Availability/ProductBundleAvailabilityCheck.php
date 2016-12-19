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

class ProductBundleAvailabilityCheck
{

    const CART_PRE_CHECK_ITEM_AVAILABILITY_FAILED = 'cart.pre.check.availability.failed';
    const CHECKOUT_PRODUCT_UNAVAILABLE_TRANSLATION_KEY = 'product.unavailable';
    const CART_PRE_CHECK_ITEM_AVAILABILITY_EMPTY = 'cart.pre.check.availability.failed.empty';

    const STOCK_TRANSLATION_PARAMETER = 'available';

    /**
     * @var \Spryker\Zed\AvailabilityCartConnector\Dependency\Facade\AvailabilityCartConnectorToAvailabilityInterface
     */
    protected $availabilityFacade;

    /**
     * @var \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface
     */
    protected $productBundleQueryContainer;

    /**
     * @var ProductBundleToAvailabilityQueryContainerInterface
     */
    protected $availabilityQueryContainer;

    /**
     * @param ProductBundleToAvailabilityInterface $availabilityFacade
     * @param \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface $productBundleQueryContainer
     * @param ProductBundleToAvailabilityQueryContainerInterface $availabilityQueryContainer
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
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function checkCheckoutAvailability(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $currentCartItems = $quoteTransfer->getItems();

        $checkoutErrorMessages = new ArrayObject();
        $uniqueBundleItems = $this->getUniqueBundleItems($quoteTransfer);

        foreach ($uniqueBundleItems as $bundleItemTransfer) {
            $bundledItems = $this->productBundleQueryContainer
                ->queryBundleProductBySku($bundleItemTransfer->getSku())
                ->find();

            if (!$this->isAllBundleItemsAvailable($currentCartItems, $bundledItems)) {
                $checkoutErrorMessages[] = $this->createCheckoutResponseTransfer();
            }
        }

        if (count($checkoutErrorMessages) > 0) {
            $checkoutResponse->setIsSuccess(false);

            foreach ($checkoutErrorMessages as $checkoutErrorTransfer) {
                $checkoutResponse->addError($checkoutErrorTransfer);
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
        $currentCartItems = $cartChangeTransfer->getQuote()->getItems();
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {

            $bundledItems = $this->productBundleQueryContainer
                ->queryBundleProductBySku($itemTransfer->getSku())
                ->find();

            if (count($bundledItems) > 0) {
                if (!$this->isAllBundleItemsAvailable($currentCartItems, $bundledItems)) {
                    $availabilityEntity = $this->availabilityQueryContainer
                        ->querySpyAvailabilityBySku($itemTransfer->getSku())
                        ->findOne();

                    $cartPreCheckErrorMessages[] = $this->createItemIsNotAvailableMessageTransfer($availabilityEntity->getQuantity());
                }
            } else {
                $sku = $itemTransfer->getSku();
                $itemQuantity = $itemTransfer->getQuantity();

                if (!$this->checkIfItemIsSellable($currentCartItems, $sku, $itemQuantity)) {
                    $available = $this->availabilityFacade->calculateStockForProduct($sku);
                    $cartPreCheckErrorMessages->append(
                        $this->createItemIsNotAvailableMessageTransfer($available)
                    );
                }
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
     * @param string $stock
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createItemIsNotAvailableMessageTransfer($stock)
    {
        $translationKey = $this->getItemAvailabilityTranslationKey($stock);
        return $this->createMessageTransfer($stock, $translationKey);
    }

    /**
     * @return \Generated\Shared\Transfer\CheckoutErrorTransfer
     */
    protected function createCheckoutResponseTransfer()
    {
        $checkoutErrorTransfer = new CheckoutErrorTransfer();
        $checkoutErrorTransfer->setMessage(self::CHECKOUT_PRODUCT_UNAVAILABLE_TRANSLATION_KEY);

        return $checkoutErrorTransfer;
    }

    /**
     * @param int $stock
     *
     * @return string
     */
    protected function getItemAvailabilityTranslationKey($stock)
    {
        $translationKey = self::CART_PRE_CHECK_ITEM_AVAILABILITY_FAILED;
        if ($stock <= 0) {
            $translationKey = self::CART_PRE_CHECK_ITEM_AVAILABILITY_EMPTY;
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
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMessageTransfer($stock, $translationKey)
    {
        $messageTranfer = new MessageTransfer();
        $messageTranfer->setValue($translationKey);
        $messageTranfer->setParameters([
            self::STOCK_TRANSLATION_PARAMETER => $stock
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
     * @param ArrayObject $items
     * @param ObjectCollection $bundledProducts
     *
     * @return bool
     */
    protected function isAllBundleItemsAvailable(ArrayObject $items, ObjectCollection $bundledProducts)
    {
        foreach ($bundledProducts as $productBundleEntity) {
            $bundledProductConcreteEntity = $productBundleEntity->getSpyProductRelatedByFkBundledProduct();

            $sku = $bundledProductConcreteEntity->getSku();
            if (!$this->checkIfItemIsSellable($items, $sku)) {
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

}
