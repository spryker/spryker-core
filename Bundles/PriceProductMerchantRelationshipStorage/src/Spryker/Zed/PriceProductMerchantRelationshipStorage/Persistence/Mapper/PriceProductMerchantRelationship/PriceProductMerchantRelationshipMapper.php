<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\Mapper\PriceProductMerchantRelationship;

use Generated\Shared\Transfer\PriceProductMerchantRelationshipTransfer;
use Generated\Shared\Transfer\SpyPriceProductMerchantRelationshipEntityTransfer;

class PriceProductMerchantRelationshipMapper implements PriceProductMerchantRelationshipMapperInterface
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
    ): PriceProductMerchantRelationshipTransfer {
        return $priceProductMerchantRelationshipTransfer->fromArray($spyPriceProductMerchantRelationshipEntityTransfer->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyPriceProductMerchantRelationshipEntityTransfer[] $spyPriceProductMerchantRelationshipEntityTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductMerchantRelationshipTransfer[]
     */
    public function mapEntitiesToPriceProductMerchantRelationshipTransferCollection(array $spyPriceProductMerchantRelationshipEntityTransfers): array
    {
        $priceProductMerchantRelationshipTransferCollection = [];

        foreach ($spyPriceProductMerchantRelationshipEntityTransfers as $spyPriceProductMerchantRelationshipEntityTransfer) {
            $priceProductMerchantRelationshipTransferCollection[] = $this->mapEntityToPriceProductMerchantRelationshipTransfer(
                $spyPriceProductMerchantRelationshipEntityTransfer,
                new PriceProductMerchantRelationshipTransfer()
            );
        }

        return $priceProductMerchantRelationshipTransferCollection;
    }
}
