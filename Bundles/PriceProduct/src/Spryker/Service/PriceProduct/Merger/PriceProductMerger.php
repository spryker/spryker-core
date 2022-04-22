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
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $abstractPriceProductTransfers
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $concretePriceProductTransfers
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function mergeConcreteAndAbstractPrices(
        array $abstractPriceProductTransfers,
        array $concretePriceProductTransfers
    ): array {
        $abstractPriceProductTransfers = $this->groupPriceProductTransfers($abstractPriceProductTransfers);
        $concretePriceProductTransfers = $this->groupPriceProductTransfers($concretePriceProductTransfers);

        $abstractPriceProductTransfers = $this->filterNotMergeableProductAbstractPrices(
            $abstractPriceProductTransfers,
            $concretePriceProductTransfers,
        );

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
        /** @var \Generated\Shared\Transfer\MoneyValueTransfer $abstractMoneyValueTransfer */
        $abstractMoneyValueTransfer = $abstractPriceProductTransfer->getMoneyValue();
        /** @var \Generated\Shared\Transfer\MoneyValueTransfer $concreteMoneyValueTransfer */
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
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $concretePriceProductTransfers
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    protected function addConcreteNotMergedPrices(
        array $concretePriceProductTransfers,
        array $priceProductTransfers
    ): array {
        return array_values($priceProductTransfers + $concretePriceProductTransfers);
    }

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    protected function groupPriceProductTransfers(array $priceProductTransfers): array
    {
        $priceProductTransfersResult = [];

        foreach ($priceProductTransfers as $priceProductTransfer) {
            $priceProductTransfersResult[$priceProductTransfer->requireGroupKey()->getGroupKey()] = $priceProductTransfer;
        }

        return $priceProductTransfersResult;
    }

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param string $priceType
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    protected function filterNotMergeableProductPricesByPriceType(array $priceProductTransfers, string $priceType): array
    {
        return array_filter($priceProductTransfers, function (PriceProductTransfer $priceProductTransfer) use ($priceType) {
            return $priceProductTransfer->getIsMergeable() === true || $priceProductTransfer->getPriceDimensionOrFail()->getTypeOrFail() !== $priceType;
        });
    }

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $abstractPriceProductTransfers
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $concretePriceProductTransfers
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    protected function filterNotMergeableProductAbstractPrices(
        array $abstractPriceProductTransfers,
        array $concretePriceProductTransfers
    ): array {
        foreach ($concretePriceProductTransfers as $concretePriceProductTransfer) {
            if (!$concretePriceProductTransfer->getIsMergeable()) {
                $abstractPriceProductTransfers = $this->filterNotMergeableProductPricesByPriceType(
                    $abstractPriceProductTransfers,
                    $concretePriceProductTransfer->getPriceDimensionOrFail()->getTypeOrFail(),
                );
            }
        }

        return $abstractPriceProductTransfers;
    }
}
