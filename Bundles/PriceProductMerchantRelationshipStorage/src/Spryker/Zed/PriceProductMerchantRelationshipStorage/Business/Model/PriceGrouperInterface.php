<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model;

use Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer;

interface PriceGrouperInterface
{
    /**
     * @see \Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface::groupPriceProductCollection()
     *
     * Specification:
     *  - Groups provided transfers by currency, price mode and price type.
     *  - Merges the grouped prices with provided existing price data (optional).
     *  - Filters empty prices.
     *
     * @param \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer
     * @param array $pricesData
     *
     * @return \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer
     */
    public function groupPricesData(
        PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer,
        array $pricesData = []
    ): PriceProductMerchantRelationshipStorageTransfer;
}
