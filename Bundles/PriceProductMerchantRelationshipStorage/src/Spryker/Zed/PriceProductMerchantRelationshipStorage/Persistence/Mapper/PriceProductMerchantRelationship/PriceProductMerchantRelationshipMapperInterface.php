<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\Mapper\PriceProductMerchantRelationship;

use Generated\Shared\Transfer\PriceProductMerchantRelationshipTransfer;
use Generated\Shared\Transfer\SpyPriceProductMerchantRelationshipEntityTransfer;

interface PriceProductMerchantRelationshipMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyPriceProductMerchantRelationshipEntityTransfer $spyPriceProductMerchantRelationshipEntityTransfer
     * @param \Generated\Shared\Transfer\PriceProductMerchantRelationshipTransfer $priceProductMerchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductMerchantRelationshipTransfer
     */
    public function mapEntityToPriceProductMerchantRelationshipTransfer(
        SpyPriceProductMerchantRelationshipEntityTransfer $spyPriceProductMerchantRelationshipEntityTransfer,
        PriceProductMerchantRelationshipTransfer $priceProductMerchantRelationshipTransfer
    ): PriceProductMerchantRelationshipTransfer;

    /**
     * @param \Generated\Shared\Transfer\SpyPriceProductMerchantRelationshipEntityTransfer[] $spyPriceProductMerchantRelationshipEntityTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductMerchantRelationshipTransfer[]
     */
    public function mapEntitiesToPriceProductMerchantRelationshipTransferCollection(array $spyPriceProductMerchantRelationshipEntityTransfers): array;
}
