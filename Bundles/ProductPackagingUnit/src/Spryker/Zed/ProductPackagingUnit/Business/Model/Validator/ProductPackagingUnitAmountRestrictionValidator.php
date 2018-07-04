<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\Validator;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitAmountTransfer;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit\ProductPackagingUnitReaderInterface;

class ProductPackagingUnitAmountRestrictionValidator implements ProductPackagingUnitAmountRestrictionValidatorInterface
{
    protected const ERROR_AMOUNT_MIN_NOT_FULFILLED = 'cart.pre.check.amount.min.failed';
    protected const ERROR_AMOUNT_MAX_NOT_FULFILLED = 'cart.pre.check.amount.max.failed';
    protected const ERROR_AMOUNT_INTERVAL_NOT_FULFILLED = 'cart.pre.check.amount.interval.failed';

    protected const PRODUCT_PACKAGING_UNIT_AMOUNT_DEFAULT_VALUES = [
        ProductPackagingUnitAmountTransfer::AMOUNT_INTERVAL => 1,
        ProductPackagingUnitAmountTransfer::AMOUNT_MIN => 1,
    ];

    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit\ProductPackagingUnitReaderInterface
     */
    protected $productPackagingUnitReader;

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit\ProductPackagingUnitReaderInterface $productPackagingUnitReader
     */
    public function __construct(ProductPackagingUnitReaderInterface $productPackagingUnitReader)
    {
        $this->productPackagingUnitReader = $productPackagingUnitReader;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validateItemAddition(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        $responseTransfer = (new CartPreCheckResponseTransfer())->setIsSuccess(true);

        $this->validate($cartChangeTransfer, $responseTransfer);

        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return array
     */
    protected function getPackagingUnitItemTransfers(CartChangeTransfer $cartChangeTransfer): array
    {
        $packagingUnitItemTransfers = [];
        $itemTransfers = $cartChangeTransfer->getItems();

        foreach ($itemTransfers as $itemTransfer) {
            if (!$itemTransfer->getAmountSalesUnit()) {
                continue;
            }
            $packagingUnitItemTransfers[] = $itemTransfer;
        }

        return $packagingUnitItemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function validate(CartChangeTransfer $cartChangeTransfer, CartPreCheckResponseTransfer $responseTransfer): void
    {
        $packagingUnitItemTransfers = $this->getPackagingUnitItemTransfers($cartChangeTransfer);

        if ($packagingUnitItemTransfers) {
            $changedSkuMapByGroupKey = $this->getChangedSkuMap($packagingUnitItemTransfers);
            $cartAmountMapByGroupKey = $this->getItemAddCartAmountMap($packagingUnitItemTransfers, $cartChangeTransfer);
            $productAmountTransferMapBySku = $this->getProductAmountTransferMap($packagingUnitItemTransfers);

            foreach ($cartAmountMapByGroupKey as $productGroupKey => $amount) {
                $productSku = $changedSkuMapByGroupKey[$productGroupKey];
                $this->validateItem($productSku, $amount, $productAmountTransferMapBySku[$productSku], $responseTransfer);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitAmountTransfer[].
     */
    protected function getProductAmountTransferMap(array $items): array
    {
        $skus = $this->getChangedSkuMap($items);

        $productPackagingUnitTransfers = $this->productPackagingUnitReader->findProductPackagingUnitBySkus($skus);

        $productPackagingUnitAmountTransferMap = $this->mapProductPackagingUnitAmountTransfersBySku($productPackagingUnitTransfers);
        $productPackagingUnitAmountTransferMap = $this->replaceMissingSkus($productPackagingUnitAmountTransferMap, $skus);

        return $productPackagingUnitAmountTransferMap;
    }

    /**
     * @param array $productPackagingUnitAmountTransferMap
     * @param string[] $requiredSkus
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitAmountTransfer[]
     */
    protected function replaceMissingSkus(array $productPackagingUnitAmountTransferMap, array $requiredSkus): array
    {
        $defaultProductPackagingAmountTransfer = $this->getDefaultProductPackagingAmountTransfer();

        foreach ($requiredSkus as $sku) {
            if (isset($productPackagingUnitAmountTransferMap[$sku])) {
                continue;
            }

            $productPackagingUnitAmountTransferMap[$sku] = $defaultProductPackagingAmountTransfer;
        }

        return $productPackagingUnitAmountTransferMap;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductPackagingUnitAmountTransfer
     */
    protected function getDefaultProductPackagingAmountTransfer(): ProductPackagingUnitAmountTransfer
    {
        return (new ProductPackagingUnitAmountTransfer())->fromArray(static::PRODUCT_PACKAGING_UNIT_AMOUNT_DEFAULT_VALUES);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTransfer[] $productPackagingUnitTransfers
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitAmountTransfer[]
     */
    protected function mapProductPackagingUnitAmountTransfersBySku(array $productPackagingUnitTransfers): array
    {
        $productPackagingUnitAmountTransferMap = [];

        foreach ($productPackagingUnitTransfers as $productPackagingUnitTransfer) {
            $productPackagingUnitAmountTransferMap[$productPackagingUnitTransfer->getIdProductPackagingUnit()] = $productPackagingUnitTransfer->getProductPackagingUnitAmount();
        }

        return $productPackagingUnitAmountTransferMap;
    }

    /**
     * @param string $sku
     * @param float $amount
     * @param \Generated\Shared\Transfer\ProductPackagingUnitAmountTransfer $productPackagingUnitAmountTransfer
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function validateItem(string $sku, float $amount, ProductPackagingUnitAmountTransfer $productPackagingUnitAmountTransfer, CartPreCheckResponseTransfer $responseTransfer): void
    {
        $min = $productPackagingUnitAmountTransfer->getAmountMin();
        $max = $productPackagingUnitAmountTransfer->getAmountMax();
        $interval = $productPackagingUnitAmountTransfer->getAmountInterval();

        if ($amount != 0 && $amount < $min) {
            $this->addViolation(static::ERROR_AMOUNT_MIN_NOT_FULFILLED, $sku, $min, $amount, $responseTransfer);
        }

        if ($amount != 0 && ($amount - $min) % $interval != 0) {
            $this->addViolation(static::ERROR_AMOUNT_INTERVAL_NOT_FULFILLED, $sku, $interval, $amount, $responseTransfer);
        }

        if ($max != null && $amount > $max) {
            $this->addViolation(static::ERROR_AMOUNT_MAX_NOT_FULFILLED, $sku, $max, $amount, $responseTransfer);
        }
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

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return string[]
     */
    protected function getChangedSkuMap(array $items): array
    {
        $skuMap = [];

        foreach ($items as $itemTransfer) {
            $skuMap[$itemTransfer->getGroupKey()] = $itemTransfer->getSku();
        }

        return $skuMap;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $items
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return int[]
     */
    protected function getItemAddCartAmountMap(array $items, CartChangeTransfer $cartChangeTransfer): array
    {
        $quoteAmountMapByGroupKey = $this->getQuoteAmountMap($cartChangeTransfer);
        $cartAmountMap = [];

        foreach ($items as $itemTransfer) {
            $productGroupKey = $itemTransfer->getGroupKey();
            $cartAmountMap[$productGroupKey] = $itemTransfer->getAmount();

            if (isset($quoteAmountMapByGroupKey[$productGroupKey])) {
                $cartAmountMap[$productGroupKey] += $quoteAmountMapByGroupKey[$productGroupKey];
            }
        }

        return $cartAmountMap;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return array
     */
    protected function getQuoteAmountMap(CartChangeTransfer $cartChangeTransfer): array
    {
        $quoteAmountMap = [];
        foreach ($cartChangeTransfer->getQuote()->getItems() as $itemTransfer) {
            $quoteAmountMap[$itemTransfer->getGroupKey()] = $itemTransfer->getAmount();
        }

        return $quoteAmountMap;
    }
}
