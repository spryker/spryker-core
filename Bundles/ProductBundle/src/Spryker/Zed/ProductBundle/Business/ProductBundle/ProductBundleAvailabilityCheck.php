<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityInterface;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface;

class ProductBundleAvailabilityCheck
{
    const CART_PRE_CHECK_AVAILABILITY_FAILED = 'cart.pre.check.availability.failed';
    const CART_PRE_CHECK_AVAILABILITY_EMPTY = 'cart.pre.check.availability.failed.empty';
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
        $cartPreCheckResponseTransfer = new CartPreCheckResponseTransfer();
        $cartPreCheckResponseTransfer->setIsSuccess(true);

        $messages = new \ArrayObject();
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {

            $en = SpyProductQuery::create()->findOneBySku($itemTransfer->getSku());

            $bundledProducts = $this->productBundleQueryContainer
                ->queryBundledProduct($en->getIdProduct())
                ->find();

            if (count($bundledProducts) > 0) {

                foreach ($bundledProducts as $productBundleEntity) {
                    $productEntity = $productBundleEntity->getSpyProductRelatedByFkProduct();
                    $sku = $productEntity->getSku();
                    $itemQuantity = $productBundleEntity->getQuantity() * $itemTransfer->getQuantity();

                    $message = $this->checkAvailability($cartChangeTransfer, $sku, $itemQuantity, $cartPreCheckResponseTransfer);
                    if ($message) {
                        $messages[] = $message;
                    }
                }
            } else {
                $sku = $itemTransfer->getSku();
                $itemQuantity = $itemTransfer->getQuantity();

                $message = $this->checkAvailability($cartChangeTransfer, $sku, $itemQuantity, $cartPreCheckResponseTransfer);
                if ($message) {
                    $messages[] = $message;
                }
            }

        }

        $cartPreCheckResponseTransfer->setMessages($messages);

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param string $sku
     *
     * @return int
     */
    protected function calculateCurrentCartQuantityForGivenSku(CartChangeTransfer $cartChangeTransfer, $sku)
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
     * @param int $stock
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createItemIsNotAvailableMessageTransfer($stock)
    {
        $translationKey = $this->getTranslationKey($stock);

        $messageTranfer = new MessageTransfer();
        $messageTranfer->setValue($translationKey);
        $messageTranfer->setParameters([
            self::STOCK_TRANSLATION_PARAMETER => $stock
        ]);

        return $messageTranfer;
    }

    /**
     * @param int $stock
     *
     * @return string
     */
    protected function getTranslationKey($stock)
    {
        $translationKey = self::CART_PRE_CHECK_AVAILABILITY_FAILED;
        if ($stock <= 0) {
            $translationKey = self::CART_PRE_CHECK_AVAILABILITY_EMPTY;
        }
        return $translationKey;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param string $sku
     * @param int $itemQuantity
     * @param CartPreCheckResponseTransfer $cartPreCheckResponseTransfer
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function checkAvailability(
        CartChangeTransfer $cartChangeTransfer,
        $sku,
        $itemQuantity,
        CartPreCheckResponseTransfer $cartPreCheckResponseTransfer
    ) {
        $currentItemQuantity = $this->calculateCurrentCartQuantityForGivenSku(
            $cartChangeTransfer,
            $sku
        );
        $currentItemQuantity += $itemQuantity;

        $isSellable = $this->availabilityFacade->isProductSellable(
            $sku,
            $currentItemQuantity
        );

        $message = null;
        if (!$isSellable) {
            $stock = $this->availabilityFacade->calculateStockForProduct($sku);
            $cartPreCheckResponseTransfer->setIsSuccess(false);
            $message = $this->createItemIsNotAvailableMessageTransfer($stock);
        }

        return $message;
    }
}
