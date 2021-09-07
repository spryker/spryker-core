<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Validator\PriceProductOffer\Constraint;

use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class VolumePriceHasBasePriceProductConstraint extends SymfonyConstraint
{
    /**
     * @var string
     */
    protected const MESSAGE = 'A Price for Quantity of 2 or above requires a Price for 1 for this set of inputs Store, Currency, and Quantity.';

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return static::MESSAGE;
    }

    /**
     * @return string
     */
    public function getTargets(): string
    {
        return static::CLASS_CONSTRAINT;
    }
}
