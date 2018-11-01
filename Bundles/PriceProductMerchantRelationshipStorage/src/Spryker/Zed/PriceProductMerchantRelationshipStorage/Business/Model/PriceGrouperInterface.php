<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model;

interface PriceGrouperInterface
{
    /**
     * @param array $products
     * @param string $productPrimaryIdentifier
     * @param string $productSkuIdentifier
     *
     * @return \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer[]
     */
    public function getGroupedPrices(array $products, string $productPrimaryIdentifier, string $productSkuIdentifier): array;
}
