<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Business\Generator;

class ProductOfferReferenceGenerator implements ProductOfferReferenceGeneratorInterface
{
    /**
     * @var string
     */
    protected const PREFIX_PRODUCT_OFFER_REFERENCE = 'offer';

    /**
     * @param int $idProductOffer
     *
     * @return string
     */
    public function generateProductOfferReferenceById(int $idProductOffer): string
    {
        return sprintf('%s%d', static::PREFIX_PRODUCT_OFFER_REFERENCE, $idProductOffer);
    }
}
