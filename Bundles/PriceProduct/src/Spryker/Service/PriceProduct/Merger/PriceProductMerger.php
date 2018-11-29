<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProduct\Merger;

use Generated\Shared\Transfer\PriceProductTransfer;

class PriceProductMerger implements PriceProductMergerInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $abstractPriceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $concretePriceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function mergeConcreteAndAbstractPrices(
        array $abstractPriceProductTransfers,
        array $concretePriceProductTransfers
    ): array {
        $abstractPriceProductTransfers = $this->prefillPriceProductTransferKeys($abstractPriceProductTransfers);
        $concretePriceProductTransfers = $this->prefillPriceProductTransferKeys($concretePriceProductTransfers);

        $priceProductTransfers = [];

        foreach ($abstractPriceProductTransfers as $abstractPriceProductTransferKey => $abstractPriceProductTransfer) {
            if (array_key_exists($abstractPriceProductTransferKey, $concretePriceProductTransfers)) {
                $priceProductTransfers[$abstractPriceProductTransferKey] = $this->resolveConcreteProductPrice($abstractPriceProductTransfer, $concretePriceProductTransfers[$abstractPriceProductTransferKey]);
                continue;
            }

            $priceProductTransfers[$abstractPriceProductTransferKey] = $abstractPriceProductTransfer;
        }

        $priceProductTransfers = $this->addConcreteNotMergedPrices($concretePriceProductTransfers, $priceProductTransfers);

        return $priceProductTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $abstractPriceProductTransfer
     * @param \Generated\Shared\Transfer\PriceProductTransfer $concretePriceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function resolveConcreteProductPrice(
        PriceProductTransfer $abstractPriceProductTransfer,
        PriceProductTransfer $concretePriceProductTransfer
    ): PriceProductTransfer {
        $abstractMoneyValueTransfer = $abstractPriceProductTransfer->getMoneyValue();
        $concreteMoneyValueTransfer = $concretePriceProductTransfer->getMoneyValue();

        if ($concreteMoneyValueTransfer->getGrossAmount() === null) {
            $concreteMoneyValueTransfer->setGrossAmount($abstractMoneyValueTransfer->getGrossAmount());
        }

        if ($concreteMoneyValueTransfer->getNetAmount() === null) {
            $concreteMoneyValueTransfer->setNetAmount($abstractMoneyValueTransfer->getNetAmount());
        }

        return $concretePriceProductTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $concretePriceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function addConcreteNotMergedPrices(
        array $concretePriceProductTransfers,
        array $priceProductTransfers
    ): array {
        return array_values($priceProductTransfers + $concretePriceProductTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function prefillPriceProductTransferKeys(array $priceProductTransfers): array
    {
        $priceProductTransfersResult = [];
        foreach ($priceProductTransfers as $priceProductTransfer) {
            $priceProductTransfersResult[$this->buildPriceProductIdentifier($priceProductTransfer)] = $priceProductTransfer;
        }

        return $priceProductTransfersResult;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return string
     */
    protected function buildPriceProductIdentifier(PriceProductTransfer $priceProductTransfer): string
    {
        $priceProductTransfer->requireMoneyValue();
        $priceProductTransfer->requirePriceTypeName();
        $priceProductTransfer->getMoneyValue()->requireCurrency();
        $priceProductTransfer->getMoneyValue()->getCurrency()->requireCode();
        $priceProductTransfer->requirePriceDimension();
        $priceProductTransfer->requirePriceType();

        return implode('-', array_filter($this->getIdentifiersPath($priceProductTransfer)));
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return array
     */
    protected function getIdentifiersPath(PriceProductTransfer $priceProductTransfer): array
    {
        $priceDimensionTransfer = $priceProductTransfer->getPriceDimension();

        return array_merge([
                $priceProductTransfer->getMoneyValue()->getCurrency()->getCode(),
                $priceProductTransfer->getPriceTypeName(),
                $priceProductTransfer->getMoneyValue()->getFkStore(),
                $priceProductTransfer->getPriceType()->getPriceModeConfiguration(),
            ], array_values($priceDimensionTransfer->toArray()));
    }
}
