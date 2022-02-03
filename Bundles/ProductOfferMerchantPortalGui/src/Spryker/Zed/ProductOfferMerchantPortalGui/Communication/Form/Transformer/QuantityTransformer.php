<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\Transformer;

use Spryker\DecimalObject\Decimal;
use Symfony\Component\Form\DataTransformerInterface;

class QuantityTransformer implements DataTransformerInterface
{
    /**
     * @param \Spryker\DecimalObject\Decimal|null $value
     *
     * @return float|null
     */
    public function transform($value)
    {
        if ($value === null) {
            return null;
        }

        return $value->toFloat();
    }

    /**
     * @param float|null $value
     *
     * @return \Spryker\DecimalObject\Decimal|null
     */
    public function reverseTransform($value)
    {
        if ($value === null) {
            return null;
        }

        return (new Decimal($value));
    }
}
