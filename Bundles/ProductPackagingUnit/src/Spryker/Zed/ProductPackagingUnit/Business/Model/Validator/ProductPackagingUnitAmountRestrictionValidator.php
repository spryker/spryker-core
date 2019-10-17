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
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface;

class ProductPackagingUnitAmountRestrictionValidator implements ProductPackagingUnitAmountRestrictionValidatorInterface
{
    protected const ERROR_AMOUNT_MIN_NOT_FULFILLED = 'cart.pre.check.amount.min.failed';
    protected const ERROR_AMOUNT_MAX_NOT_FULFILLED = 'cart.pre.check.amount.max.failed';
    protected const ERROR_AMOUNT_INTERVAL_NOT_FULFILLED = 'cart.pre.check.amount.interval.failed';
    protected const ERROR_AMOUNT_IS_NOT_VARIABLE = 'cart.pre.check.amount.is_not_variable.failed';

    protected const DIVISION_SCALE = 10;

    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface
     */
    protected $productPackagingUnitRepository;

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface $productPackagingUnitRepository
     */
    public function __construct(ProductPackagingUnitRepositoryInterface $productPackagingUnitRepository)
    {
        $this->productPackagingUnitRepository = $productPackagingUnitRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validateItemAddition(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        $cartPreCheckResponseTransfer = (new CartPreCheckResponseTransfer())->setIsSuccess(true);

        $cartPreCheckResponseTransfer = $this->validateItemsAmounts($cartChangeTransfer, $cartPreCheckResponseTransfer);

        return $cartPreCheckResponseTransfer->setIsSuccess(
            $cartPreCheckResponseTransfer->getMessages()->count() === 0
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $cartPreCheckResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    protected function validateItemsAmounts(CartChangeTransfer $cartChangeTransfer, CartPreCheckResponseTransfer $cartPreCheckResponseTransfer): CartPreCheckResponseTransfer
    {
        $itemTransfers = $this->selectItemTransfersWithAmountSalesUnit($cartChangeTransfer);

        if (!$itemTransfers) {
            return $cartPreCheckResponseTransfer;
        }

        $changedSkuMapByGroupKey = $this->getChangedSkuMap($itemTransfers);
        $cartAmountMapByGroupKey = $this->getItemAddCartAmountMap($itemTransfers, $cartChangeTransfer);
        $productPackagingUnitAmountTransferMapBySku = $this->getProductPackagingUnitAmountTransferMap($itemTransfers);

        foreach ($cartAmountMapByGroupKey as $productGroupKey => $cartAmount) {
            $productSku = $changedSkuMapByGroupKey[$productGroupKey];
            $cartPreCheckResponseTransfer = $this->validateItem($productSku, $cartAmount, $productPackagingUnitAmountTransferMapBySku[$productSku], $cartPreCheckResponseTransfer);
        }

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function selectItemTransfersWithAmountSalesUnit(CartChangeTransfer $cartChangeTransfer): array
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
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return string[]
     */
    protected function getChangedSkuMap(array $itemTransfers): array
    {
        $skuMap = [];

        foreach ($itemTransfers as $itemTransfer) {
            $skuMap[$itemTransfer->getGroupKey()] = $itemTransfer->getSku();
        }

        return $skuMap;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Spryker\DecimalObject\Decimal[]
     */
    protected function getItemAddCartAmountMap(array $itemTransfers, CartChangeTransfer $cartChangeTransfer): array
    {
        $quoteAmountMapByGroupKey = $this->getQuoteAmountMap($cartChangeTransfer);
        /** @var \Spryker\DecimalObject\Decimal[] $cartAmountMap */
        $cartAmountMap = [];

        foreach ($itemTransfers as $itemTransfer) {
            $productGroupKey = $itemTransfer->getGroupKey();
            $amountPerQuantity = $itemTransfer->getAmount()->divide($itemTransfer->getQuantity(), static::DIVISION_SCALE);
            $cartAmountMap[$productGroupKey] = $amountPerQuantity;

            if (isset($quoteAmountMapByGroupKey[$productGroupKey])) {
                $cartAmountMap[$productGroupKey] = $cartAmountMap[$productGroupKey]->add(
                    $quoteAmountMapByGroupKey[$productGroupKey]
                );
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
            if (!$itemTransfer->getAmount()) {
                continue;
            }
            $amountPerQuantity = $itemTransfer->getAmount()->divide($itemTransfer->getQuantity(), static::DIVISION_SCALE);
            $quoteAmountMap[$itemTransfer->getGroupKey()] = $amountPerQuantity;
        }

        return $quoteAmountMap;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitAmountTransfer[].
     */
    protected function getProductPackagingUnitAmountTransferMap(array $itemTransfers): array
    {
        $skus = $this->getChangedSkuMap($itemTransfers);

        $productPackagingUnitAmountTransferMap = $this->mapProductPackagingUnitAmountTransfersBySku($itemTransfers);

        return $productPackagingUnitAmountTransferMap;
    }

    /**
     * @param string $sku
     * @param \Spryker\DecimalObject\Decimal $amount
     * @param \Generated\Shared\Transfer\ProductPackagingUnitAmountTransfer $productPackagingUnitAmountTransfer
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $cartPreCheckResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    protected function validateItem(
        string $sku,
        Decimal $amount,
        ProductPackagingUnitAmountTransfer $productPackagingUnitAmountTransfer,
        CartPreCheckResponseTransfer $cartPreCheckResponseTransfer
    ): CartPreCheckResponseTransfer {
        $productPackagingUnitAmountTransfer
            ->requireIsVariable()
            ->requireDefaultAmount();

        $defaultAmount = $productPackagingUnitAmountTransfer->getDefaultAmount();

        if (!$productPackagingUnitAmountTransfer->getIsVariable()) {
            if (!$amount->mod($defaultAmount)->isZero()) {
                $cartPreCheckResponseTransfer->addMessage($this->createMessageTransfer(static::ERROR_AMOUNT_IS_NOT_VARIABLE, $sku, $defaultAmount, $amount));
            }

            return $cartPreCheckResponseTransfer;
        }

        if ($amount->isZero()) {
            return $cartPreCheckResponseTransfer;
        }

        $min = $productPackagingUnitAmountTransfer->getAmountMin();
        $max = $productPackagingUnitAmountTransfer->getAmountMax();
        $interval = $productPackagingUnitAmountTransfer->getAmountInterval();

        if ($min !== null) {
            if ($amount->lessThan($min)) {
                $cartPreCheckResponseTransfer->addMessage($this->createMessageTransfer(static::ERROR_AMOUNT_MIN_NOT_FULFILLED, $sku, $min, $amount));
            }

            if ($interval !== null && !$amount->subtract($min)->mod($interval)->isZero()) {
                $cartPreCheckResponseTransfer->addMessage($this->createMessageTransfer(static::ERROR_AMOUNT_INTERVAL_NOT_FULFILLED, $sku, $interval, $amount));
            }
        }

        if ($max !== null && $amount->greaterThan($max)) {
            $cartPreCheckResponseTransfer->addMessage($this->createMessageTransfer(static::ERROR_AMOUNT_MAX_NOT_FULFILLED, $sku, $max, $amount));
        }

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @param string $message
     * @param string $sku
     * @param \Spryker\DecimalObject\Decimal $restrictionValue
     * @param \Spryker\DecimalObject\Decimal $actualValue
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMessageTransfer(
        string $message,
        string $sku,
        Decimal $restrictionValue,
        Decimal $actualValue
    ): MessageTransfer {
        return (new MessageTransfer())
            ->setValue($message)
            ->setParameters([
                '%sku%' => $sku,
                '%restrictionValue%' => $restrictionValue->toString(),
                '%actualValue%' => $actualValue->toString(),
            ]);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitAmountTransfer[]
     */
    protected function mapProductPackagingUnitAmountTransfersBySku(array $itemTransfers): array
    {
        $productPackagingUnitAmountTransferMap = [];
        foreach ($itemTransfers as $itemTransfer) {
            $productPackagingUnitTransfer = $this->productPackagingUnitRepository
                ->findProductPackagingUnitByProductSku($itemTransfer->getSku());

            if ($productPackagingUnitTransfer) {
                $productPackagingUnitAmountTransferMap[$itemTransfer->getSku()] = $productPackagingUnitTransfer->getProductPackagingUnitAmount();
            }
        }

        return $productPackagingUnitAmountTransferMap;
    }
}
