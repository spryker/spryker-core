<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationship\Business\Model;

use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Spryker\Shared\PriceProductMerchantRelationship\PriceProductMerchantRelationshipConstants;

class PriceProductDimensionExpander implements PriceProductDimensionExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductDimensionTransfer $priceProductDimensionTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductDimensionTransfer
     */
    public function expand(PriceProductDimensionTransfer $priceProductDimensionTransfer): PriceProductDimensionTransfer
    {
        $priceProductDimensionTransfer->setType(PriceProductMerchantRelationshipConstants::PRICE_DIMENSION_MERCHANT_RELATIONSHIP);

        return $priceProductDimensionTransfer;
    }
}
