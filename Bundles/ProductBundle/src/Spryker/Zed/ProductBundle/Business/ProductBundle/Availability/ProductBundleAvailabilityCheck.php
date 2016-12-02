<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Availability;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\ProductBundle\Persistence\SpyProductBundleQuery;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityInterface;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface;

class ProductBundleAvailabilityCheck
{

    const CART_PRE_CHECK_ITEM_AVAILABILITY_FAILED = 'cart.pre.check.availability.failed';
    const CART_PRE_CHECK_ITEM_AVAILABILITY_EMPTY = 'cart.pre.check.availability.failed.empty';

    const STOCK_TRANSLATION_PARAMETER = 'stock';

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
    public function checkCondition(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {

    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function checkCartAvailability(CartChangeTransfer $cartChangeTransfer)
    {
        $messages = new \ArrayObject();
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {

            $bundledItems = SpyProductBundleQuery::create()
                ->useSpyProductRelatedByFkProductQuery()
                    ->filterBySku($itemTransfer->getSku())
                ->endUse()
                ->find();

            if (count($bundledItems) > 0) {
                $this->checkBundledItems($cartChangeTransfer, $bundledItems, $itemTransfer, $messages);
            } else {
                $sku = $itemTransfer->getSku();
                $itemQuantity = $itemTransfer->getQuantity();

                if (!$this->checkIfItemIsSellable($cartChangeTransfer, $sku, $itemQuantity)) {
                    $messages->append(
                        $this->createItemIsNotAvailableMessageTransfer($sku)
                    );
                }
            }
        }

        return $this->createCartPreCheckResponseTransfer($messages);
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param string $sku
     *
     * @return int
     */
    protected function getAccumulatedItemQuantityForGivenSku(CartChangeTransfer $cartChangeTransfer, $sku)
    {
        $quantity = 0;
        foreach ($cartChangeTransfer->getQuote()->getItems() as $itemTransfer) {
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
        $stock = $this->availabilityFacade->calculateStockForProduct($sku);
        $translationKey = $this->getItemAvailabilityTranslationKey($stock);

        return $this->createMessageTransfer($stock, $translationKey);
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
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param string $sku
     * @param int $itemQuantity
     *
     * @return bool
     */
    protected function checkIfItemIsSellable(
        CartChangeTransfer $cartChangeTransfer,
        $sku,
        $itemQuantity
    ) {
        $currentItemQuantity = $this->getAccumulatedItemQuantityForGivenSku(
            $cartChangeTransfer,
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
     * @param string $messages
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    protected function createCartPreCheckResponseTransfer($messages)
    {
        $cartPreCheckResponseTransfer = new CartPreCheckResponseTransfer();
        $cartPreCheckResponseTransfer->setIsSuccess(count($messages) == 0);
        $cartPreCheckResponseTransfer->setMessages($messages);

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param ObjectCollection $bundledProducts
     * @param ItemTransfer $itemTransfer
     * @param \ArrayObject $messages
     *
     */
    protected function checkBundledItems(
        CartChangeTransfer $cartChangeTransfer,
        ObjectCollection $bundledProducts,
        ItemTransfer $itemTransfer,
        \ArrayObject $messages
    ) {

        foreach ($bundledProducts as $productBundleEntity) {
            $bundledProductConcreteEntity = $productBundleEntity->getSpyProductRelatedByFkBundledProduct();

            $sku = $bundledProductConcreteEntity->getSku();
            $itemQuantity = $productBundleEntity->getQuantity() * $itemTransfer->getQuantity();

            if (!$this->checkIfItemIsSellable($cartChangeTransfer, $sku, $itemQuantity)) {
                $messages->append(
                    $this->createItemIsNotAvailableMessageTransfer($sku)
                );
            }
        }
    }
}
