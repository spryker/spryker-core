<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferSearch\Business\ProductAbstract;

interface ProductAbstractReaderInterface
{
    /**
     * @param int[] $merchantIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByMerchantIds(array $merchantIds): array;

    /**
     * @param int[] $merchantProfileIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByMerchantProfileIds(array $merchantProfileIds): array;

    /**
     * @param int[] $productOfferIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByProductOfferIds(array $productOfferIds): array;
}
