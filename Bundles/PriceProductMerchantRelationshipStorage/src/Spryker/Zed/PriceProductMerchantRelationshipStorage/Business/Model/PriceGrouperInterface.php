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
     * @param \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer
     * @param array $pricesData
     *
     * @return \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer
     */
    public function groupAndMergePricesData(
        PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer,
        array $pricesData
    ): PriceProductMerchantRelationshipStorageTransfer;
}
