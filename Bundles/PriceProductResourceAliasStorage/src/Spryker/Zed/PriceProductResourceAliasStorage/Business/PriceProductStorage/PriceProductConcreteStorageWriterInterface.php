<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductResourceAliasStorage\Business\PriceProductStorage;

interface PriceProductConcreteStorageWriterInterface
{
    /**
     * @param int[] $productConcreteIds
     *
     * @return void
     */
    public function updatePriceProductConcreteStorageSkusByProductConcreteIds(array $productConcreteIds): void;

    /**
     * @param int[] $priceProductStoreIds
     *
     * @return void
     */
    public function updatePriceProductConcreteStorageSkusByStoreIds(array $priceProductStoreIds): void;
}
