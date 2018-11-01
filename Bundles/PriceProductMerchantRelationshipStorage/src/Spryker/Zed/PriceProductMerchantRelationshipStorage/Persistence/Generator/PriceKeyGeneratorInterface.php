<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\Generator;

interface PriceKeyGeneratorInterface
{
    /**
     * @param string $storeName
     * @param int $productId
     * @param int $companyBusinessUnitId
     *
     * @return string
     */
    public function buildPriceKey(string $storeName, int $productId, int $companyBusinessUnitId): string;
}
