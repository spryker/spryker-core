<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityInterface;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface;

class ProductBundleAvailabilityCheck
{
    const CART_PRE_CHECK_ITEM_AVAILABILITY_FAILED = 'cart.pre.check.availability.failed';
    const CART_PRE_CHECK_ITEM_AVAILABILITY_EMPTY = 'cart.pre.check.availability.failed.empty';

    const CART_PRE_CHECK_BUNDLE_AVAILABILITY_FAILED = 'cart.pre.check.bundle.availability.failed';
    const CART_PRE_CHECK_BUNDLE_AVAILABILITY_EMPTY = 'cart.pre.check.bundle.availability.failed.empty';

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
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function checkCartAvailability(CartChangeTransfer $cartChangeTransfer)
    {
        $messages = new \ArrayObject();
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {

            $productEntity = SpyProductQuery::create()
                ->findOneBySku($itemTransfer->getSku());

            $bundledProducts = $this->productBundleQueryContainer
                ->queryBundledProduct($productEntity->getIdProduct())
                ->find();

            if (count($bundledProducts) > 0) {
                foreach ($bundledProducts as $productBundleEntity) {
                    $relatedProductEntity = $productBundleEntity->getSpyProductRelatedByFkProduct();

                    $sku = $relatedProductEntity->getSku();
                    $itemQuantity = $productBundleEntity->getQuantity() * $itemTransfer->getQuantity();

                    $isSellable = $this->isSellable($cartChangeTransfer, $sku, $itemQuantity);
                    if (!$isSellable) {
                        $messages[] = $this->createBundleIsNotAvailableMessageTransfer($sku);
                    }
                }
            } else {
                $sku = $itemTransfer->getSku();
                $itemQuantity = $itemTransfer->getQuantity();

                $isSellable = $this->isSellable($cartChangeTransfer, $sku, $itemQuantity);
                if (!$isSellable) {
                    $messages[] = $this->createItemIsNotAvailableMessageTransfer($sku);
                }
            }

        }

        $cartPreCheckResponseTransfer = new CartPreCheckResponseTransfer();
        $cartPreCheckResponseTransfer->setIsSuccess(count($messages) == 0);
        $cartPreCheckResponseTransfer->setMessages($messages);

        return $cartPreCheckResponseTransfer;
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
     * @param int $sku
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createBundleIsNotAvailableMessageTransfer($sku)
    {
        $stock = $this->availabilityFacade->calculateStockForProduct($sku);
        $translationKey = $this->getBundleAvailabilityTranslationKey($stock);

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
     * @param int $stock
     *
     * @return string
     */
    protected function getBundleAvailabilityTranslationKey($stock)
    {
        $translationKey = self::CART_PRE_CHECK_BUNDLE_AVAILABILITY_FAILED;
        if ($stock <= 0) {
            $translationKey = self::CART_PRE_CHECK_BUNDLE_AVAILABILITY_EMPTY;
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
    protected function isSellable(
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
}
