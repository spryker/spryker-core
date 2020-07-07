<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOfferAvailabilitiesRestApi\Processor\RestResponseBuilder;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface ProductOfferAvailabilityRestResponseBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferAvailabilityStorageTransfer[] $productOfferAvailabilityStorageTransfers
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function createProductOfferAvailabilityRestResources(array $productOfferAvailabilityStorageTransfers): array;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductOfferAvailabilityEmptyRestResponse(): RestResponseInterface;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $productOfferAvailabilityRestResource
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductOfferAvailabilityRestResponse(RestResourceInterface $productOfferAvailabilityRestResource): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductOfferIdNotSpecifiedErrorResponse(): RestResponseInterface;
}
