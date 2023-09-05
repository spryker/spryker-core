<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypesServicePointsResourceRelationship\Processor\Builder;

use Generated\Shared\Transfer\GlueResourceTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

class ServiceTypeResourceBuilder implements ServiceTypeResourceBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected RestResourceBuilderInterface $restResourceBuilder;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(RestResourceBuilderInterface $restResourceBuilder)
    {
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResourceTransfer $serviceTypeGlueResourceTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createServiceTypesRestResource(GlueResourceTransfer $serviceTypeGlueResourceTransfer): RestResourceInterface
    {
        return $this->restResourceBuilder->createRestResource(
            $serviceTypeGlueResourceTransfer->getTypeOrFail(),
            $serviceTypeGlueResourceTransfer->getIdOrFail(),
            $serviceTypeGlueResourceTransfer->getAttributesOrFail(),
        );
    }
}
