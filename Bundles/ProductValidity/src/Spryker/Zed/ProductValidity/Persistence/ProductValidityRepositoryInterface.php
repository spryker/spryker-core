<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductValidity\Persistence;

interface ProductValidityRepositoryInterface
{
    /**
     * Result format:
     * [
     *     $idProductConcrete => ProductValidityTransfer,
     *     ...,
     * ]
     *
     * @param array<int> $productConcreteIds
     *
     * @return array<int, \Generated\Shared\Transfer\ProductValidityTransfer>
     */
    public function getProductValidityTransfersIndexedByIdProductConcrete(array $productConcreteIds): array;
}
