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
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $concretePriceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $abstractPriceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function mergeConcreteAndAbstractPrices(array $concretePriceProductTransfers, array $abstractPriceProductTransfers): array
    {
        $priceProductTransfers = [];
        foreach ($abstractPriceProductTransfers as $abstractPriceProductTransfer) {
            $abstractKey = $this->buildPriceProductIdentifier($abstractPriceProductTransfer);

            $priceProductTransfers = $this->mergeConcreteProduct(
                $concretePriceProductTransfers,
                $abstractKey,
                $abstractPriceProductTransfer,
                $priceProductTransfers
            );

            if (!isset($priceProductTransfers[$abstractKey])) {
                $priceProductTransfers[$abstractKey] = $abstractPriceProductTransfer;
            }
        }

        return $this->addConcreteNotMergedPrices($concretePriceProductTransfers, $priceProductTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return string
     */
    protected function buildPriceProductIdentifier(PriceProductTransfer $priceProductTransfer): string
    {
        $priceProductTransfer->requireMoneyValue()->requirePriceTypeName();
        $priceDimensionTransfer = $priceProductTransfer->requirePriceDimension()->getPriceDimension();

        return implode(
            '-',
            array_filter([
                $priceProductTransfer->getMoneyValue()->getCurrency()->getCode(),
                $priceProductTransfer->getPriceTypeName(),
                $priceProductTransfer->getMoneyValue()->getFkStore(),
            ] + array_values($priceDimensionTransfer->toArray()))
        );
    }

    /**
     * @param array $concretePriceProductTransfers
     * @param string $abstractKey
     * @param \Generated\Shared\Transfer\PriceProductTransfer $abstractPriceProductTransfer
     * @param array $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function mergeConcreteProduct(
        array $concretePriceProductTransfers,
        string $abstractKey,
        PriceProductTransfer $abstractPriceProductTransfer,
        array $priceProductTransfers
    ) {
        foreach ($concretePriceProductTransfers as $concretePriceProductTransfer) {
            $concreteKey = $this->buildPriceProductIdentifier($concretePriceProductTransfer);

            if ($abstractKey !== $concreteKey) {
                continue;
            }

            $priceProductTransfers[$concreteKey] = $this->resolveConcreteProductPrice(
                $abstractPriceProductTransfer,
                $concretePriceProductTransfer
            );
        }

        if (!isset($priceProductTransfers[$abstractKey])) {
            $priceProductTransfers[$abstractKey] = $abstractPriceProductTransfer;
        }

        return $priceProductTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductAbstractTransfer
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function resolveConcreteProductPrice(
        PriceProductTransfer $priceProductAbstractTransfer,
        PriceProductTransfer $priceProductConcreteTransfer
    ) {
        $abstractMoneyValueTransfer = $priceProductAbstractTransfer->getMoneyValue();
        $concreteMoneyValueTransfer = $priceProductConcreteTransfer->getMoneyValue();

        if ($concreteMoneyValueTransfer->getGrossAmount() === null) {
            $concreteMoneyValueTransfer->setGrossAmount($abstractMoneyValueTransfer->getGrossAmount());
        }

        if ($concreteMoneyValueTransfer->getNetAmount() === null) {
            $concreteMoneyValueTransfer->setNetAmount($abstractMoneyValueTransfer->getNetAmount());
        }

        return $priceProductConcreteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $concretePriceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function addConcreteNotMergedPrices(array $concretePriceProductTransfers, array $priceProductTransfers)
    {
        foreach ($concretePriceProductTransfers as $concretePriceProductTransfer) {
            $concreteKey = $this->buildPriceProductIdentifier($concretePriceProductTransfer);

            if (isset($priceProductTransfers[$concreteKey])) {
                continue;
            }

            $priceProductTransfers[$concreteKey] = $concretePriceProductTransfer;
        }

        return $priceProductTransfers;
    }
}
