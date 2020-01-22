<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAvailabilitiesRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface ProductAbstractAvailabilityRestResponseBuilderInterface
{
    /**
     * @param string $productAbstractSku
     * @param \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer $productAbstractAvailabilityTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createProductAbstractAvailabilityResource(
        string $productAbstractSku,
        ProductAbstractAvailabilityTransfer $productAbstractAvailabilityTransfer
    ): RestResourceInterface;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $productAbstractAvailabilityRestResource
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductAbstractAvailabilityResponse(RestResourceInterface $productAbstractAvailabilityRestResource): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductAbstractSkuIsNotSpecifiedErrorResponse(): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductAbstractAvailabilityNotFoundErrorResponse(): RestResponseInterface;
}
