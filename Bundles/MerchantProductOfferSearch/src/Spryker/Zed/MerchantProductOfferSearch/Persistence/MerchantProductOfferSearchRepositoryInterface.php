<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferSearch\Persistence;

interface MerchantProductOfferSearchRepositoryInterface
{
    /**
     * @param array<int> $productAbstractIds
     *
     * @return array
     */
    public function getMerchantDataByProductAbstractIds(array $productAbstractIds): array;

    /**
     * @param array<int> $merchantIds
     *
     * @return array<int>
     */
    public function getProductAbstractIdsByMerchantIds(array $merchantIds): array;

    /**
     * @param array<int> $productOfferIds
     *
     * @return array<int>
     */
    public function getProductAbstractIdsByProductOfferIds(array $productOfferIds): array;
}
