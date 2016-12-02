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
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\ProductBundle\Persistence\SpyProductBundleQuery;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityInterface;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface;
use \ArrayObject;

class ProductBundleAvailabilityCheck
{

    const CART_PRE_CHECK_ITEM_AVAILABILITY_FAILED = 'cart.pre.check.availability.failed';
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
     * @param ProductBundleToAvailabilityInterface $availabilityFacade
     * @param \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface $productBundleQueryContainer
     */
    public function __construct(
        ProductBundleToAvailabilityInterface $availabilityFacade,
        ProductBundleQueryContainerInterface $productBundleQueryContainer
    ) {
        $this->availabilityFacade = $availabilityFacade;
        $this->productBundleQueryContainer = $productBundleQueryContainer;
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
        $messages = new \ArrayObject();
        foreach ($quoteTransfer->getBundleItems() as $bundleItemTransfer) {
            $bundledItems = SpyProductBundleQuery::create()
                ->useSpyProductRelatedByFkProductQuery()
                    ->filterBySku($bundleItemTransfer->getSku())
                ->endUse()
                ->find();

            if (!$this->isAllBundleItemsAvailable($currentCartItems, $bundledItems, $bundleItemTransfer)) {
                $messages[] = $this->createItemIsNotAvailableCheckoutMessageTransfer($bundleItemTransfer->getSku());
            }
        }

        if (count($messages) > 0) {
            $checkoutResponse->setIsSuccess(false);

            foreach ($messages as $checkoutErrorTransfer) {
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
        $messages = new \ArrayObject();
        $currentCartItems = $cartChangeTransfer->getQuote()->getItems();
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {

            $bundledItems = SpyProductBundleQuery::create()
                ->useSpyProductRelatedByFkProductQuery()
                    ->filterBySku($itemTransfer->getSku())
                ->endUse()
                ->find();

            if (count($bundledItems) > 0) {
                if (!$this->isAllBundleItemsAvailable($currentCartItems, $bundledItems, $itemTransfer)) {
                    $messages[] = $this->createItemIsNotAvailableMessageTransfer($itemTransfer->getSku());
                }
            } else {
                $sku = $itemTransfer->getSku();
                $itemQuantity = $itemTransfer->getQuantity();

                if (!$this->checkIfItemIsSellable($currentCartItems, $sku, $itemQuantity)) {
                    $messages->append(
                        $this->createItemIsNotAvailableMessageTransfer($sku)
                    );
                }
            }
        }

        return $this->createCartPreCheckResponseTransfer($messages);
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
     * @param int $sku
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createItemIsNotAvailableMessageTransfer($sku)
    {
        $available = $this->availabilityFacade->calculateStockForProduct($sku);
        $translationKey = $this->getItemAvailabilityTranslationKey($available);

        return $this->createMessageTransfer($available, $translationKey);
    }

    /**
     * @param int $sku
     *
     * @return \Generated\Shared\Transfer\CheckoutErrorTransfer
     */
    protected function createItemIsNotAvailableCheckoutMessageTransfer($sku)
    {
        $available = $this->availabilityFacade->calculateStockForProduct($sku);
        $translationKey = $this->getItemAvailabilityTranslationKey($available);

        return $this->createCheckoutResponseTransfer($available, $translationKey);
    }

    /**
     * @param int $available
     * @param string $translationKey
     *
     * @return \Generated\Shared\Transfer\CheckoutErrorTransfer
     */
    protected function createCheckoutResponseTransfer($available, $translationKey)
    {
        $checkoutErrorTransfer = new CheckoutErrorTransfer();
        $checkoutErrorTransfer->setMessage($translationKey);

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
    protected function checkIfItemIsSellable(ArrayObject $items, $sku, $itemQuantity)
    {
        $currentItemQuantity = $this->getAccumulatedItemQuantityForGivenSku(
            $items,
            $sku
        );
        $currentItemQuantity += $itemQuantity;

        return $this->availabilityFacade->isProductSellable(
            $sku,
            $currentItemQuantity
        );
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
     * @param \ArrayObject $items
     * @param ObjectCollection $bundledProducts
     * @param ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isAllBundleItemsAvailable(
        \ArrayObject $items,
        ObjectCollection $bundledProducts,
        ItemTransfer $itemTransfer
    ) {

        foreach ($bundledProducts as $productBundleEntity) {
            $bundledProductConcreteEntity = $productBundleEntity->getSpyProductRelatedByFkBundledProduct();

            $sku = $bundledProductConcreteEntity->getSku();
            $itemQuantity = $productBundleEntity->getQuantity() * $itemTransfer->getQuantity();

            if (!$this->checkIfItemIsSellable($items, $sku, $itemQuantity)) {
                return false;
            }
        }

        return true;
    }
}
