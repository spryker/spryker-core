<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProductMerchantRelationship;

use Generated\Shared\Transfer\PriceProductFilterTransfer;

interface PriceProductMerchantRelationshipServiceInterface
{
    /**
     * Specification:
     * - Filters `PriceProductTransfers` by `PriceProductFilterTransfer.priceDimension.idMerchantRelationship`.
     * - Filters out all `PriceProductTransfers` with merchant relationship if `PriceProductFilterTransfer.priceDimension.idMerchantRelationship` is not set.
     * - Filters out all `PriceProductTransfers` where `PriceProductTransfer.priceDimension.idMerchantRelationship` is different from `PriceProductFilterTransfer.priceDimension.idMerchantRelationship`.
     * - When `PriceProductFilterTransfer.priceDimension.idMerchantRelationship` is set and `PriceProductTransfer` doesn't have a merchant relationship, it is not filtered out.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function filterPriceProductsByMerchantRelationship(array $priceProductTransfers, PriceProductFilterTransfer $priceProductFilterTransfer): array;
}
