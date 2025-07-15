<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\Indexer;

class ProductClassIndexer implements ProductClassIndexerInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\ProductClassTransfer> $productClassTransfers
     *
     * @return array<string, array<\Generated\Shared\Transfer\ProductClassTransfer>>
     */
    public function getProductClassesIndexedBySku(array $productClassTransfers): array
    {
        if (!$productClassTransfers) {
            return [];
        }

        $productClassTransfersIndexedBySku = [];

        foreach ($productClassTransfers as $productClassTransfer) {
            $sku = $productClassTransfer->getSku();
            $productClassTransfersIndexedBySku[$sku][] = $productClassTransfer;
        }

        return $productClassTransfersIndexedBySku;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductClassTransfer> $productClassTransfers
     *
     * @return array<int, array<\Generated\Shared\Transfer\ProductClassTransfer>>
     */
    public function getProductClassesIndexedByProductConcreteId(array $productClassTransfers): array
    {
        if (!$productClassTransfers) {
            return [];
        }

        $productClassTransfersIndexedByProductConcreteId = [];

        foreach ($productClassTransfers as $productClassTransfer) {
            $idProductConcrete = $productClassTransfer->getIdProductConcreteOrFail();
            $productClassTransfersIndexedByProductConcreteId[$idProductConcrete][] = $productClassTransfer;
        }

        return $productClassTransfersIndexedByProductConcreteId;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductClassTransfer> $productClassTransfers
     *
     * @return array<int, array<\Generated\Shared\Transfer\ProductClassTransfer>>
     */
    public function getProductClassesIndexedByProductAbstractId(array $productClassTransfers): array
    {
        if (!$productClassTransfers) {
            return [];
        }

        $productClassTransfersIndexedByProductAbstractId = [];

        foreach ($productClassTransfers as $productClassTransfer) {
            $idProductAbstract = $productClassTransfer->getIdProductAbstractOrFail();
            $productClassTransfersIndexedByProductAbstractId[$idProductAbstract][] = $productClassTransfer;
        }

        return $productClassTransfersIndexedByProductAbstractId;
    }
}
