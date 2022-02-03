<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\Mapper;

interface CompanyBusinessUnitPriceProductMapperInterface
{
    /**
     * @param array $priceProductMerchantRelationshipTransfers
     *
     * @return array<\Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer>
     */
    public function mapPriceProductMerchantRelationshipArrayToTransfers(array $priceProductMerchantRelationshipTransfers): array;
}
