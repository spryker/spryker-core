<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantity\Business\Model\Validator;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductQuantityTransfer;
use Spryker\Zed\ProductQuantity\Business\Model\ProductQuantityReaderInterface;
use Spryker\Zed\ProductQuantity\Dependency\Service\ProductQuantityToUtilQuantityServiceInterface;
use Spryker\Zed\ProductQuantity\ProductQuantityConfig;

class ProductQuantityRestrictionValidator implements ProductQuantityRestrictionValidatorInterface
{
    protected const ERROR_QUANTITY_MIN_NOT_FULFILLED = 'cart.pre.check.quantity.min.failed';
    protected const ERROR_QUANTITY_MAX_NOT_FULFILLED = 'cart.pre.check.quantity.max.failed';
    protected const ERROR_QUANTITY_INTERVAL_NOT_FULFILLED = 'cart.pre.check.quantity.interval.failed';
    protected const ERROR_QUANTITY_INCORRECT = 'cart.pre.check.quantity.value.failed';

    protected const RESTRICTION_MIN = 'min';
    protected const RESTRICTION_MAX = 'max';
    protected const RESTRICTION_INTERVAL = 'interval';

    /**
     * @var \Spryker\Zed\ProductQuantity\Business\Model\ProductQuantityReaderInterface
     */
    protected $productQuantityReader;

    /**
     * @var \Spryker\Zed\ProductQuantity\Dependency\Service\ProductQuantityToUtilQuantityServiceInterface
     */
    protected $utilQuantityService;

    /**
     * @var \Spryker\Zed\ProductQuantity\ProductQuantityConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\ProductQuantity\Business\Model\ProductQuantityReaderInterface $productQuantityReader
     * @param \Spryker\Zed\ProductQuantity\Dependency\Service\ProductQuantityToUtilQuantityServiceInterface $utilQuantityService
     * @param \Spryker\Zed\ProductQuantity\ProductQuantityConfig $config
     */
    public function __construct(
        ProductQuantityReaderInterface $productQuantityReader,
        ProductQuantityToUtilQuantityServiceInterface $utilQuantityService,
        ProductQuantityConfig $config
    ) {
        $this->productQuantityReader = $productQuantityReader;
        $this->utilQuantityService = $utilQuantityService;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validateItemAddition(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        $responseTransfer = (new CartPreCheckResponseTransfer())->setIsSuccess(true);

        $changedSkuMapByGroupKey = $this->getChangedSkuMap($cartChangeTransfer);
        $cartQuantityMapByGroupKey = $this->getItemAddCartQuantityMap($cartChangeTransfer);
        $productQuantityTransferMapBySku = $this->getProductQuantityTransferMap($cartChangeTransfer);

        foreach ($cartQuantityMapByGroupKey as $productGroupKey => $productQuantity) {
            $productSku = $changedSkuMapByGroupKey[$productGroupKey];
            if (!$this->validateQuantityIsPositive($productSku, $productQuantity, $responseTransfer)) {
                continue;
            }
            $this->validateItem($productSku, $productQuantity, $productQuantityTransferMapBySku[$productSku], $responseTransfer);
        }

        return $responseTransfer;
    }

    /**
     * @param string $sku
     * @param float $quantity
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $responseTransfer
     *
     * @return bool
     */
    protected function validateQuantityIsPositive(string $sku, float $quantity, CartPreCheckResponseTransfer $responseTransfer): bool
    {
        $restrictedQuantity = $this->config->getDefaultMinimumQuantity();
        if ($quantity < $restrictedQuantity) {
            $this->addViolation(static::ERROR_QUANTITY_INCORRECT, $sku, $restrictedQuantity, $quantity, $responseTransfer);

            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validateItemRemoval(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        $responseTransfer = (new CartPreCheckResponseTransfer())->setIsSuccess(true);

        $changedSkuMapByGroupKey = $this->getChangedSkuMap($cartChangeTransfer);
        $cartQuantityMapByGroupKey = $this->getItemRemoveCartQuantityMap($cartChangeTransfer);
        $productQuantityTransferMap = $this->getProductQuantityTransferMap($cartChangeTransfer);

        foreach ($cartQuantityMapByGroupKey as $productGroupKey => $productQuantity) {
            $productSku = $changedSkuMapByGroupKey[$productGroupKey];
            $this->validateItem($productSku, $productQuantity, $productQuantityTransferMap[$productSku], $responseTransfer);
        }

        return $responseTransfer;
    }

    /**
     * @param string $sku
     * @param float $quantity
     * @param \Generated\Shared\Transfer\ProductQuantityTransfer $productQuantityTransfer
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function validateItem(string $sku, float $quantity, ProductQuantityTransfer $productQuantityTransfer, CartPreCheckResponseTransfer $responseTransfer): void
    {
        $min = $productQuantityTransfer->getQuantityMin();
        $max = $productQuantityTransfer->getQuantityMax();
        $interval = $productQuantityTransfer->getQuantityInterval();

        if (!$this->isQuantityEqual($quantity, 0) && $quantity < $min) {
            $this->addViolation(static::ERROR_QUANTITY_MIN_NOT_FULFILLED, $sku, $min, $quantity, $responseTransfer);
        }

        $quantityMinusMin = $this->subtractQuantities($quantity, $min);

        if (!$this->isQuantityEqual($quantity, 0) && !$this->isQuantityMultiple($quantityMinusMin, $interval)) {
            $this->addViolation(static::ERROR_QUANTITY_INTERVAL_NOT_FULFILLED, $sku, $interval, $quantity, $responseTransfer);
        }

        if ($max !== null && $quantity > $max) {
            $this->addViolation(static::ERROR_QUANTITY_MAX_NOT_FULFILLED, $sku, $max, $quantity, $responseTransfer);
        }
    }

    /**
     * @param float $firstQuantity
     * @param float $secondQuantity
     *
     * @return bool
     */
    protected function isQuantityMultiple(float $firstQuantity, float $secondQuantity): bool
    {
        return $this->utilQuantityService->isQuantityMultiple($firstQuantity, $secondQuantity);
    }

    /**
     * @param float $firstQuantity
     * @param float $secondQuantity
     *
     * @return bool
     */
    protected function isQuantityEqual(float $firstQuantity, float $secondQuantity): bool
    {
        return $this->utilQuantityService->isQuantityEqual($firstQuantity, $secondQuantity);
    }

    /**
     * @param float $firstQuantity
     * @param float $secondQuantity
     *
     * @return float
     */
    protected function subtractQuantities(float $firstQuantity, float $secondQuantity): float
    {
        return $this->utilQuantityService->subtractQuantities($firstQuantity, $secondQuantity);
    }

    /**
     * @param float $firstQuantity
     * @param float $secondQuantity
     *
     * @return float
     */
    protected function sumQuantities(float $firstQuantity, float $secondQuantity): float
    {
        return $this->utilQuantityService->sumQuantities($firstQuantity, $secondQuantity);
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return float[] Keys are product group keys, values are product quantities as 'quote.quantity + change.quantity'
     */
    protected function getItemAddCartQuantityMap(CartChangeTransfer $cartChangeTransfer): array
    {
        $quoteQuantityMapByGroupKey = $this->getQuoteQuantityMap($cartChangeTransfer);

        $cartQuantityMap = [];
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $productGroupKey = $itemTransfer->getGroupKey();
            $cartQuantityMap[$productGroupKey] = $itemTransfer->getQuantity();

            if (isset($quoteQuantityMapByGroupKey[$productGroupKey])) {
                $cartQuantityMap[$productGroupKey] = $this->sumQuantities(
                    $cartQuantityMap[$productGroupKey],
                    $quoteQuantityMapByGroupKey[$productGroupKey]
                );
            }
        }

        return $cartQuantityMap;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return float[] Keys are product group keys, values are product quantities as 'quote.quantity - change.quantity'
     */
    protected function getItemRemoveCartQuantityMap(CartChangeTransfer $cartChangeTransfer): array
    {
        $quoteQuantityMapByGroupKey = $this->getQuoteQuantityMap($cartChangeTransfer);

        $cartQuantityMap = [];
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $productGroupKey = $itemTransfer->getGroupKey();
            $cartQuantityMap[$productGroupKey] = -$itemTransfer->getQuantity();

            if (isset($quoteQuantityMapByGroupKey[$productGroupKey])) {
                $cartQuantityMap[$productGroupKey] = $this->sumQuantities(
                    $quoteQuantityMapByGroupKey[$productGroupKey],
                    $cartQuantityMap[$productGroupKey]
                );
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
            $quoteQuantityMap[$itemTransfer->getGroupKey()] = $itemTransfer->getQuantity();
        }

        return $quoteQuantityMap;
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
            ->setQuantityInterval($this->config->getDefaultInterval())
            ->setQuantityMin($this->config->getDefaultMinimumQuantity());
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
     * @param string $message
     * @param string $sku
     * @param float $restrictionValue
     * @param float $actualValue
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function addViolation(string $message, string $sku, float $restrictionValue, float $actualValue, CartPreCheckResponseTransfer $responseTransfer): void
    {
        $responseTransfer->setIsSuccess(false);
        $responseTransfer->addMessage(
            (new MessageTransfer())
                ->setValue($message)
                ->setParameters(['%sku%' => $sku, '%restrictionValue%' => $restrictionValue, '%actualValue%' => $actualValue])
        );
    }
}
