<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model;

interface PriceProductAbstractStorageWriterInterface
{
    /**
     * @param array $priceProductStoreIds
     *
     * @return void
     */
    public function publishByPriceProductStoreIds(array $priceProductStoreIds): void;

    /**
     * @param array $businessUnitProducts
     *
     * @return void
     */
    public function publishByBusinessUnitProducts(array $businessUnitProducts): void;
}
