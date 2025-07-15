<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\Indexer;

interface ProductClassIndexerInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\ProductClassTransfer> $productClassTransfers
     *
     * @return array<string, array<\Generated\Shared\Transfer\ProductClassTransfer>>
     */
    public function getProductClassesIndexedBySku(array $productClassTransfers): array;

    /**
     * @param array<\Generated\Shared\Transfer\ProductClassTransfer> $productClassTransfers
     *
     * @return array<int, array<\Generated\Shared\Transfer\ProductClassTransfer>>
     */
    public function getProductClassesIndexedByProductConcreteId(array $productClassTransfers): array;

    /**
     * @param array<\Generated\Shared\Transfer\ProductClassTransfer> $productClassTransfers
     *
     * @return array<int, array<\Generated\Shared\Transfer\ProductClassTransfer>>
     */
    public function getProductClassesIndexedByProductAbstractId(array $productClassTransfers): array;
}
