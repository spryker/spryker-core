<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Business\Validator;

use Spryker\Zed\PriceProductOffer\Business\Validator\Constraint\ValidUniqueStoreCurrencyCollectionConstraint;
use Symfony\Component\Validator\Constraints\All as AllConstraint;

class PriceProductOfferConstraintProvider implements PriceProductOfferConstraintProviderInterface
{
    /**
     * @var \Symfony\Component\Validator\Constraint[]
     */
    protected $priceProductTransferConstraints;

    /**
     * @param \Symfony\Component\Validator\Constraint[] $priceProductTransferConstraints
     */
    public function __construct(array $priceProductTransferConstraints)
    {
        $this->priceProductTransferConstraints = $priceProductTransferConstraints;
    }

    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    public function getConstraints(): array
    {
        return [
            new ValidUniqueStoreCurrencyCollectionConstraint(),
            new AllConstraint(
                $this->priceProductTransferConstraints
            ),
        ];
    }
}
