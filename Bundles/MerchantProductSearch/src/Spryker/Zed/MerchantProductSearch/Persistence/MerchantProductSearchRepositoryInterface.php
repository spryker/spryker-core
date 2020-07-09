<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductSearch\Persistence;

interface MerchantProductSearchRepositoryInterface
{
    /**
     * @param int[] $merchantIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByMerchantIds(array $merchantIds): array;

    /**
     * @param int[] $productAbstractMerchantIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByProductAbstractMerchantIds(array $productAbstractMerchantIds): array;

    /**
     * @module Store
     * @module Merchant
     *
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\ProductAbstractMerchantTransfer[]
     */
    public function getMerchantDataByProductAbstractIds(array $productAbstractIds): array;
}
