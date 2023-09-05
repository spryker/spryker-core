<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferServicePointAvailabilityCalculator;

use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCriteriaTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductOfferServicePointAvailabilityCalculator\ProductOfferServicePointAvailabilityCalculatorFactory getFactory()
 */
class ProductOfferServicePointAvailabilityCalculatorClient extends AbstractClient implements ProductOfferServicePointAvailabilityCalculatorClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCriteriaTransfer $productOfferServicePointAvailabilityCriteriaTransfer
     *
     * @return array<string, list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer>>
     */
    public function calculateProductOfferServicePointAvailabilities(
        ProductOfferServicePointAvailabilityCriteriaTransfer $productOfferServicePointAvailabilityCriteriaTransfer
    ): array {
        return $this->getFactory()
            ->createProductOfferServicePointAvailabilityCalculator()
            ->calculateProductOfferServicePointAvailabilities($productOfferServicePointAvailabilityCriteriaTransfer);
    }
}
