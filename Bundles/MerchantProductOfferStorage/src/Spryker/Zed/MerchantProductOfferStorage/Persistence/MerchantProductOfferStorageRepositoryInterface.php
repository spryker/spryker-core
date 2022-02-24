<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Persistence;

interface MerchantProductOfferStorageRepositoryInterface
{
    /**
     * @param array<int> $merchantIds
     *
     * @return array<string>
     */
    public function getProductConcreteSkusByMerchantIds(array $merchantIds): array;

    /**
     * @param array<int> $merchantIds
     * @param int $minIdProductOffer
     * @param int $total
     *
     * @return iterable<array<string>>
     */
    public function iterateProductOfferReferencesByMerchantIds(array $merchantIds, int $minIdProductOffer = 0, int $total = 1000): iterable;
}
