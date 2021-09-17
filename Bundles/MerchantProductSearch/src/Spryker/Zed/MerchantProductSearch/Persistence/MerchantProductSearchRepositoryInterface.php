<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductSearch\Persistence;

interface MerchantProductSearchRepositoryInterface
{
    /**
     * @param array<int> $merchantIds
     *
     * @return array<int>
     */
    public function getProductAbstractIdsByMerchantIds(array $merchantIds): array;

    /**
     * @param array<int> $merchantProductAbstractIds
     *
     * @return array<int>
     */
    public function getProductAbstractIdsByMerchantProductAbstractIds(array $merchantProductAbstractIds): array;

    /**
     * @module Store
     * @module Merchant
     *
     * @param array<int> $productAbstractIds
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractMerchantTransfer>
     */
    public function getMerchantDataByProductAbstractIds(array $productAbstractIds): array;
}
