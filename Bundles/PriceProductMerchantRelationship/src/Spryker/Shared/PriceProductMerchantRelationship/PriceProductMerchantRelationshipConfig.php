<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\PriceProductMerchantRelationship;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class PriceProductMerchantRelationshipConfig extends AbstractSharedConfig
{
    /**
     * Price Dimension Merchant Relationship
     */
    public const PRICE_DIMENSION_MERCHANT_RELATIONSHIP = 'PRICE_DIMENSION_MERCHANT_RELATIONSHIP';

    public const PRICE_GROSS_MODE = 'GROSS_MODE';

    /**
     * @see \Spryker\Zed\ProductManagement\Communication\Form\ProductFormAdd
     */
    public const FORM_PRICE_DIMENSION = 'price_dimension';

    /**
     * @return string
     */
    public function getPriceDimensionMerchantRelationship(): string
    {
        return static::PRICE_DIMENSION_MERCHANT_RELATIONSHIP;
    }
}
