<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Validator\PriceProductOffer;

class PriceProductOfferCollectionConstraintProvider implements PriceProductOfferConstraintProviderInterface
{
    /**
     * @var array<\Symfony\Component\Validator\Constraint>
     */
    protected $priceProductOfferCollectionTransferConstraints;

    /**
     * @param array<\Symfony\Component\Validator\Constraint> $priceProductOfferCollectionTransferConstraints
     */
    public function __construct(array $priceProductOfferCollectionTransferConstraints)
    {
        $this->priceProductOfferCollectionTransferConstraints = $priceProductOfferCollectionTransferConstraints;
    }

    /**
     * @return array<\Symfony\Component\Validator\Constraint>
     */
    public function getConstraints(): array
    {
        return $this->priceProductOfferCollectionTransferConstraints;
    }
}
