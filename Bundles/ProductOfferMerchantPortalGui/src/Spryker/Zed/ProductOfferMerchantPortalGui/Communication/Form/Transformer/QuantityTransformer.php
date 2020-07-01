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
     * @param \Spryker\DecimalObject\Decimal|null $quantity
     *
     * @return float|null
     */
    public function transform($quantity)
    {
        if ($quantity === null) {
            return null;
        }

        return $quantity->toFloat();
    }

    /**
     * @param float|null $quantity
     *
     * @return \Spryker\DecimalObject\Decimal|null
     */
    public function reverseTransform($quantity)
    {
        if ($quantity === null) {
            return null;
        }

        return (new Decimal($quantity));
    }
}
