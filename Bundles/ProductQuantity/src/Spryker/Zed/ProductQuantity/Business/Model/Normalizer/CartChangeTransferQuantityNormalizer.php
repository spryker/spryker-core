<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantity\Business\Model\Normalizer;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductQuantityTransfer;
use Spryker\Service\ProductQuantity\ProductQuantityServiceInterface;
use Spryker\Zed\ProductQuantity\Business\Model\ProductQuantityReaderInterface;

class CartChangeTransferQuantityNormalizer implements CartChangeTransferQuantityNormalizerInterface
{
    protected const MESSAGE_QUANTITY_MIN_NOT_FULFILLED = 'product-quantity.notification.quantity.min.failed';
    protected const MESSAGE_QUANTITY_MAX_NOT_FULFILLED = 'product-quantity.notification.quantity.max.failed';
    protected const MESSAGE_QUANTITY_INTERVAL_NOT_FULFILLED = 'product-quantity.notification.quantity.interval.failed';
    protected const NOTIFICATION_MESSAGE_PARAM_MIN = '%min%';
    protected const NOTIFICATION_MESSAGE_PARAM_MAX = '%max%';
    protected const NOTIFICATION_MESSAGE_PARAM_STEP = '%step%';

    protected const NORMALIZABLE_FIELD = 'quantity';

    protected const MESSAGE_TYPE = 'notification';

    /**
     * @var \Spryker\Zed\ProductQuantity\Business\Model\ProductQuantityReaderInterface
     */
    protected $productQuantityReader;

    /**
     * @var \Spryker\Service\ProductQuantity\ProductQuantityServiceInterface
     */
    protected $productQuantityService;

    /**
     * @param \Spryker\Zed\ProductQuantity\Business\Model\ProductQuantityReaderInterface $productQuantityReader
     * @param \Spryker\Service\ProductQuantity\ProductQuantityServiceInterface $productQuantityService
     */
    public function __construct(ProductQuantityReaderInterface $productQuantityReader, ProductQuantityServiceInterface $productQuantityService)
    {
        $this->productQuantityReader = $productQuantityReader;
        $this->productQuantityService = $productQuantityService;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function normalizeCartChangeTransferItems(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        $changedSkuMapByGroupKey = $this->getChangedSkuMap($cartChangeTransfer);
        $cartQuantityMapByGroupKey = $this->getItemAddCartQuantityMap($cartChangeTransfer);
        $productQuantityTransferMapBySku = $this->getProductQuantityTransferMap($cartChangeTransfer);
        $itemTransferMapBySku = $this->getItemTransferMap($cartChangeTransfer);
        $normalizedItems = new ArrayObject();

        foreach ($cartQuantityMapByGroupKey as $productGroupKey => $productQuantity) {
            $productSku = $changedSkuMapByGroupKey[$productGroupKey];

            if (!isset($productQuantityTransferMapBySku[$productSku])) {
                $normalizedItems->append($itemTransferMapBySku[$productSku]);

                continue;
            }
            $normalizedItems->append(
                $this->normalizeItemTransfer(
                    $itemTransferMapBySku[$productSku],
                    $productQuantityTransferMapBySku[$productSku],
                    $productQuantity
                )
            );
        }
        $cartChangeTransfer->setItems($normalizedItems);

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isItemTransferNormalizable(ItemTransfer $itemTransfer): bool
    {
        $normalizableFields = $itemTransfer->getNormalizableFields();

        return !empty($normalizableFields) && in_array(static::NORMALIZABLE_FIELD, $normalizableFields);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ProductQuantityTransfer $productQuantityTransfer
     * @param int $totalQuantityByGroupKey
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function normalizeItemTransfer(ItemTransfer $itemTransfer, ProductQuantityTransfer $productQuantityTransfer, int $totalQuantityByGroupKey): ItemTransfer
    {
        $nearestQuantity = $this->productQuantityService->getNearestQuantity($productQuantityTransfer, $totalQuantityByGroupKey);
        $totalQuantityByGroupKeyAfterAdjustment = $totalQuantityByGroupKey - $itemTransfer->getQuantity() + $nearestQuantity;

        if (!$this->isItemTransferQuantityValid($totalQuantityByGroupKeyAfterAdjustment, $productQuantityTransfer)) {
            return $itemTransfer;
        }

        $itemTransfer = $this->addNotificationMessage($productQuantityTransfer, $nearestQuantity, $itemTransfer);
        $itemTransfer->setQuantity($nearestQuantity);

        return $itemTransfer;
    }

    /**
     * @param int $quantity
     * @param \Generated\Shared\Transfer\ProductQuantityTransfer $productQuantityTransfer
     *
     * @return bool
     */
    protected function isItemTransferQuantityValid(int $quantity, ProductQuantityTransfer $productQuantityTransfer): bool
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
     * Returns array where keys are product group keys, values are product quantities as 'quote.quantity + change.quantity'.
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return int[]
     */
    protected function getItemAddCartQuantityMap(CartChangeTransfer $cartChangeTransfer): array
    {
        $quoteQuantityMapByGroupKey = $this->getQuoteQuantityMap($cartChangeTransfer);

        $cartQuantityMap = [];
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (!$this->isItemTransferNormalizable($itemTransfer)) {
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
            if (!$this->isItemTransferNormalizable($itemTransfer)) {
                continue;
            }
            $quoteQuantityMap[$itemTransfer->getGroupKey()] = $itemTransfer->getQuantity();
        }

        return $quoteQuantityMap;
    }

    /**
     * Returns array where keys are product skus, values are itemTransfer.
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function getItemTransferMap(CartChangeTransfer $cartChangeTransfer): array
    {
        $itemTransferMap = [];
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (!$this->isItemTransferNormalizable($itemTransfer)) {
                continue;
            }

            $itemTransferMap[$itemTransfer->getSku()] = $itemTransfer;
        }

        return $itemTransferMap;
    }

    /**
     * Returns array where keys are product SKUs.
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductQuantityTransfer[]
     */
    protected function getProductQuantityTransferMap(CartChangeTransfer $cartChangeTransfer): array
    {
        $skus = $this->getChangedSkuMap($cartChangeTransfer);
        $productQuantityTransfers = $this->productQuantityReader->findProductQuantityTransfersByProductSku($skus);

        $productQuantityTransferMap = $this->mapProductQuantityTransfersBySku($productQuantityTransfers);

        return $productQuantityTransferMap;
    }

    /**
     * Returns array where keys are group keys, values are skus
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return string[]
     */
    protected function getChangedSkuMap(CartChangeTransfer $cartChangeTransfer): array
    {
        $skuMap = [];
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (!$this->isItemTransferNormalizable($itemTransfer)) {
                continue;
            }

            $skuMap[$itemTransfer->getGroupKey()] = $itemTransfer->getSku();
        }

        return $skuMap;
    }

    /**
     * Returns array where keys are product SKUs.
     *
     * @param \Generated\Shared\Transfer\ProductQuantityTransfer[] $productQuantityTransfers
     *
     * @return \Generated\Shared\Transfer\ProductQuantityTransfer[]
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
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function addNotificationMessage(
        ProductQuantityTransfer $productQuantityTransfer,
        int $nearestQuantity,
        ItemTransfer $itemTransfer
    ): ItemTransfer {
        $notificationMessage = $this->createNotificationMessage($productQuantityTransfer, $nearestQuantity, $itemTransfer->getQuantity());

        if ($notificationMessage !== null) {
            $itemTransfer->addMessage($notificationMessage);
        }

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductQuantityTransfer $productQuantityTransfer
     * @param int $nearestQuantity
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\MessageTransfer|null
     */
    protected function createNotificationMessage(ProductQuantityTransfer $productQuantityTransfer, int $nearestQuantity, int $quantity): ?MessageTransfer
    {
        $min = $productQuantityTransfer->getQuantityMin();
        $max = $productQuantityTransfer->getQuantityMax();
        $interval = $productQuantityTransfer->getQuantityInterval();

        if ($quantity < $min) {
            return $this->buildNotificationMessage(
                static::MESSAGE_QUANTITY_MIN_NOT_FULFILLED,
                static::NOTIFICATION_MESSAGE_PARAM_MIN,
                $nearestQuantity
            );
        }

        if (($quantity - $min) % $interval !== 0) {
            return $this->buildNotificationMessage(
                static::MESSAGE_QUANTITY_INTERVAL_NOT_FULFILLED,
                static::NOTIFICATION_MESSAGE_PARAM_STEP,
                $productQuantityTransfer->getQuantityInterval()
            );
        }

        if ($max !== null && $quantity > $max) {
            return $this->buildNotificationMessage(
                static::MESSAGE_QUANTITY_MAX_NOT_FULFILLED,
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
            ->setType(static::MESSAGE_TYPE)
            ->setParameters([$notificationParam => $nearestQuantity]);
    }
}
