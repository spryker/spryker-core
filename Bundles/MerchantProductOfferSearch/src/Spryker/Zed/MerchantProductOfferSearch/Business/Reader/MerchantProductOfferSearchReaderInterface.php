<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferSearch\Business\Reader;

interface MerchantProductOfferSearchReaderInterface
{
    /**
     * @param int[] $productAbstractIds
     *
     * @return string[][]
     */
    public function getMerchantNamesByProductAbstractIds(array $productAbstractIds): array;

    /**
     * @param int[] $productAbstractIds
     *
     * @return string[][]
     */
    public function getMerchantReferencesByProductAbstractIds(array $productAbstractIds): array;
}
