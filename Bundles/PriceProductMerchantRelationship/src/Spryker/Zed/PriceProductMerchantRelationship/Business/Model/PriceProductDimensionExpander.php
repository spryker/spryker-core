<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationship\Business\Model;

use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Spryker\Zed\PriceProductMerchantRelationship\PriceProductMerchantRelationshipConfig;

class PriceProductDimensionExpander implements PriceProductDimensionExpanderInterface
{
    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationship\PriceProductMerchantRelationshipConfig
     */
    protected $priceProductMerchantRelationshipConfig;

    /**
     * @param \Spryker\Zed\PriceProductMerchantRelationship\PriceProductMerchantRelationshipConfig $priceProductMerchantRelationshipConfig
     */
    public function __construct(PriceProductMerchantRelationshipConfig $priceProductMerchantRelationshipConfig)
    {
        $this->priceProductMerchantRelationshipConfig = $priceProductMerchantRelationshipConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductDimensionTransfer $priceProductDimensionTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductDimensionTransfer
     */
    public function expand(PriceProductDimensionTransfer $priceProductDimensionTransfer): PriceProductDimensionTransfer
    {
        $priceProductDimensionTransfer->setType($this->priceProductMerchantRelationshipConfig->getPriceDimensionMerchantRelationship());

        return $priceProductDimensionTransfer;
    }
}
