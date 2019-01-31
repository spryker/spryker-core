<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantity\Business\Model\Normalizer;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductQuantityTransfer;
use Spryker\Zed\ProductQuantity\Business\Model\ProductQuantityReaderInterface;
use Spryker\Zed\ProductQuantity\Business\Model\Rounder\ProductQuantityRounderInterface;

class ProductQuantityRestrictionNormalizer implements ProductQuantityRestrictionNormalizerInterface
{
    protected const NOTIFICATION_MESSAGE_QUANTITY_MIN_NOT_FULFILLED = 'product-quantity.notification.quantity.min.failed';
    protected const NOTIFICATION_MESSAGE_QUANTITY_MAX_NOT_FULFILLED = 'product-quantity.notification.quantity.max.failed';
    protected const NOTIFICATION_MESSAGE_QUANTITY_INTERVAL_NOT_FULFILLED = 'product-quantity.notification.quantity.interval.failed';
    protected const NOTIFICATION_MESSAGE_PARAM_MIN = '%min%';
    protected const NOTIFICATION_MESSAGE_PARAM_MAX = '%max%';
    protected const NOTIFICATION_MESSAGE_PARAM_STEP = '%step%';

    protected const NORMALIZABLE_FIELD = 'quantity';

    /**
     * @var \Spryker\Zed\ProductQuantity\Business\Model\ProductQuantityReaderInterface
     */
    protected $productQuantityReader;

    /**
     * @var \Spryker\Zed\ProductQuantity\Business\Model\Rounder\ProductQuantityRounderInterface
     */
    protected $productQuantityRounder;

    /**
     * @param \Spryker\Zed\ProductQuantity\Business\Model\ProductQuantityReaderInterface $productQuantityReader
     * @param \Spryker\Zed\ProductQuantity\Business\Model\Rounder\ProductQuantityRounderInterface $productQuantityRounder
     */
    public function __construct(ProductQuantityReaderInterface $productQuantityReader, ProductQuantityRounderInterface $productQuantityRounder)
    {
        $this->productQuantityReader = $productQuantityReader;
        $this->productQuantityRounder = $productQuantityRounder;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function normalizeCartChangeTransfer(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        $changedSkuMapByGroupKey = $this->getChangedSkuMap($cartChangeTransfer);
        $cartQuantityMapByGroupKey = $this->getItemAddCartQuantityMap($cartChangeTransfer);
        $productQuantityTransferMapBySku = $this->getProductQuantityTransferMap($cartChangeTransfer);
        $itemTransferMapBySku = $this->getItemTransferMap($cartChangeTransfer);

        foreach ($cartQuantityMapByGroupKey as $productGroupKey => $productQuantity) {
            $productSku = $changedSkuMapByGroupKey[$productGroupKey];
            $this->normalizeItem($itemTransferMapBySku[$productSku], $productQuantityTransferMapBySku[$productSku], $productQuantity);
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isCartItemNormalizable(ItemTransfer $itemTransfer): bool
    {
        $normalizableFields = $itemTransfer->getNormalizableFields();

        if (!empty($normalizableFields) && in_array(static::NORMALIZABLE_FIELD, $normalizableFields)) {
            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ProductQuantityTransfer $productQuantityTransfer
     * @param int $totalQuantityByGroupKey
     *
     * @return void
     */
    protected function normalizeItem(ItemTransfer $itemTransfer, ProductQuantityTransfer $productQuantityTransfer, int $totalQuantityByGroupKey): void
    {
        $sku = $itemTransfer->getSku();
        $min = $productQuantityTransfer->getQuantityMin();
        $max = $productQuantityTransfer->getQuantityMax();
        $interval = $productQuantityTransfer->getQuantityInterval();

        $nearestQuantity = $this->productQuantityRounder->getNearestQuantity($productQuantityTransfer, $totalQuantityByGroupKey);

        $totalQuantityByGroupKeyAfterAdjustment = $totalQuantityByGroupKey - $itemTransfer->getQuantity() + $nearestQuantity;

        if (!$this->isItemQuantityValid($totalQuantityByGroupKeyAfterAdjustment, $productQuantityTransfer)) {
            return;
        }

        $notificationMessage = $this->findNotificationMessage($productQuantityTransfer, $nearestQuantity, $itemTransfer->getQuantity());
        if ($notificationMessage !== null) {
            $itemTransfer->addNotificationMessage($notificationMessage);
        }
        $itemTransfer->setQuantity($nearestQuantity);
    }

    /**
     * @param int $quantity
     * @param \Generated\Shared\Transfer\ProductQuantityTransfer $productQuantityTransfer
     *
     * @return bool
     */
    protected function isItemQuantityValid(int $quantity, ProductQuantityTransfer $productQuantityTransfer): bool
    {
        $min = $productQuantityTransfer->getQuantityMin();
        $max = $productQuantityTransfer->getQuantityMax();
        $interval = $productQuantityTransfer->getQuantityInterval();

        if ($quantity < $min) {
            return false;
        }

        if (($quantity - $min) % $interval !== 0) {
            return false;
        }

        if ($max !== null && $quantity > $max) {
            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return int[] Keys are product group keys, values are product quantities as 'quote.quantity + change.quantity'
     */
    protected function getItemAddCartQuantityMap(CartChangeTransfer $cartChangeTransfer): array
    {
        $quoteQuantityMapByGroupKey = $this->getQuoteQuantityMap($cartChangeTransfer);

        $cartQuantityMap = [];
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (!$this->isCartItemNormalizable($itemTransfer)) {
                continue;
            }
            $productGroupKey = $itemTransfer->getGroupKey();
            $cartQuantityMap[$productGroupKey] = $itemTransfer->getQuantity();

            if (isset($quoteQuantityMapByGroupKey[$productGroupKey])) {
                $cartQuantityMap[$productGroupKey] += $quoteQuantityMapByGroupKey[$productGroupKey];
            }
        }

        return $cartQuantityMap;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return array
     */
    protected function getQuoteQuantityMap(CartChangeTransfer $cartChangeTransfer): array
    {
        $quoteQuantityMap = [];
        foreach ($cartChangeTransfer->getQuote()->getItems() as $itemTransfer) {
            if (!$this->isCartItemNormalizable($itemTransfer)) {
                continue;
            }
            $quoteQuantityMap[$itemTransfer->getGroupKey()] = $itemTransfer->getQuantity();
        }

        return $quoteQuantityMap;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return array
     */
    protected function getItemTransferMap(CartChangeTransfer $cartChangeTransfer): array
    {
        $itemTransferMap = [];
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (!$this->isCartItemNormalizable($itemTransfer)) {
                continue;
            }

            $itemTransferMap[$itemTransfer->getSku()] = $itemTransfer;
        }

        return $itemTransferMap;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductQuantityTransfer[] Keys are product SKUs.
     */
    protected function getProductQuantityTransferMap(CartChangeTransfer $cartChangeTransfer): array
    {
        $skus = $this->getChangedSkuMap($cartChangeTransfer);
        $productQuantityTransfers = $this->productQuantityReader->findProductQuantityTransfersByProductSku($skus);

        $productQuantityTransferMap = $this->mapProductQuantityTransfersBySku($productQuantityTransfers);
        $productQuantityTransferMap = $this->replaceMissingSkus($productQuantityTransferMap, $skus);

        return $productQuantityTransferMap;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return string[] Keys are group keys, values are skus
     */
    protected function getChangedSkuMap(CartChangeTransfer $cartChangeTransfer)
    {
        $skuMap = [];
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (!$this->isCartItemNormalizable($itemTransfer)) {
                continue;
            }

            $skuMap[$itemTransfer->getGroupKey()] = $itemTransfer->getSku();
        }

        return $skuMap;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductQuantityTransfer
     */
    protected function getDefaultProductQuantityTransfer(): ProductQuantityTransfer
    {
        return (new ProductQuantityTransfer())
            ->setQuantityInterval(1)
            ->setQuantityMin(1);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductQuantityTransfer[] $productQuantityTransferMap
     * @param string[] $requiredSkus
     *
     * @return \Generated\Shared\Transfer\ProductQuantityTransfer[]
     */
    protected function replaceMissingSkus(array $productQuantityTransferMap, array $requiredSkus): array
    {
        $defaultProductQuantityTransfer = $this->getDefaultProductQuantityTransfer();

        foreach ($requiredSkus as $sku) {
            if (isset($productQuantityTransferMap[$sku])) {
                continue;
            }

            $productQuantityTransferMap[$sku] = $defaultProductQuantityTransfer;
        }

        return $productQuantityTransferMap;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductQuantityTransfer[] $productQuantityTransfers
     *
     * @return \Generated\Shared\Transfer\ProductQuantityTransfer[] Keys are product SKUs.
     */
    protected function mapProductQuantityTransfersBySku(array $productQuantityTransfers): array
    {
        $productQuantityTransferMap = [];
        foreach ($productQuantityTransfers as $productQuantityTransfer) {
            $productQuantityTransferMap[$productQuantityTransfer->getProduct()->getSku()] = $productQuantityTransfer;
        }

        return $productQuantityTransferMap;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductQuantityTransfer $productQuantityTransfer
     * @param int $nearestQuantity
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\MessageTransfer|null
     */
    protected function findNotificationMessage(ProductQuantityTransfer $productQuantityTransfer, int $nearestQuantity, int $quantity): ?MessageTransfer
    {
        $min = $productQuantityTransfer->getQuantityMin();
        $max = $productQuantityTransfer->getQuantityMax();
        $interval = $productQuantityTransfer->getQuantityInterval();

        if ($quantity < $min) {
            return $this->buildNotificationMessage(
                static::NOTIFICATION_MESSAGE_QUANTITY_MIN_NOT_FULFILLED,
                static::NOTIFICATION_MESSAGE_PARAM_MIN,
                $nearestQuantity
            );
        }

        if (($quantity - $min) % $interval !== 0) {
            return $this->buildNotificationMessage(
                static::NOTIFICATION_MESSAGE_QUANTITY_INTERVAL_NOT_FULFILLED,
                static::NOTIFICATION_MESSAGE_PARAM_STEP,
                $nearestQuantity
            );
        }

        if ($max !== null && $quantity > $max) {
            return $this->buildNotificationMessage(
                static::NOTIFICATION_MESSAGE_QUANTITY_MAX_NOT_FULFILLED,
                static::NOTIFICATION_MESSAGE_PARAM_MAX,
                $nearestQuantity
            );
        }

        return null;
    }

    /**
     * @param string $notificationMessage
     * @param string $notificationParam
     * @param int $nearestQuantity
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function buildNotificationMessage(string $notificationMessage, string $notificationParam, int $nearestQuantity): MessageTransfer
    {
        return (new MessageTransfer())
            ->setValue($notificationMessage)
            ->setParameters([$notificationParam => $nearestQuantity]);
    }
}
