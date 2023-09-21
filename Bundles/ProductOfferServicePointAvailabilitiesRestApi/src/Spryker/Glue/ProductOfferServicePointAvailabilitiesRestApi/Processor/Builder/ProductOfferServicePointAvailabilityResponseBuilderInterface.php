<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOfferServicePointAvailabilitiesRestApi\Processor\Builder;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface ProductOfferServicePointAvailabilityResponseBuilderInterface
{
    /**
     * @param array<string, list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer>> $productOfferServicePointAvailabilities
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductOfferServicePointAvailabilityCollectionRestResponse(
        array $productOfferServicePointAvailabilities
    ): RestResponseInterface;
}
