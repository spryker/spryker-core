<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAvailabilitiesRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestConcreteProductAvailabilityAttributesTransfer;
use Generated\Shared\Transfer\SpyAvailabilityEntityTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\ProductAvailabilitiesRestApi\ProductAvailabilitiesRestApiConfig;

class ConcreteProductAvailabilitiesResourceMapper implements ConcreteProductAvailabilitiesResourceMapperInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(RestResourceBuilderInterface $restResourceBuilder)
    {
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyAvailabilityEntityTransfer $availabilityEntityTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function mapConcreteProductsAvailabilityTransferToRestResource(SpyAvailabilityEntityTransfer $availabilityEntityTransfer): RestResourceInterface
    {
        $restProductsConcreteAvailabilityAttributesTransfer = (new RestConcreteProductAvailabilityAttributesTransfer())
            ->fromArray($availabilityEntityTransfer->toArray(true), true);
        $restProductsConcreteAvailabilityAttributesTransfer->setAvailability($availabilityEntityTransfer->getQuantity() > 0);

        return $this->restResourceBuilder->createRestResource(
            ProductAvailabilitiesRestApiConfig::RESOURCE_CONCRETE_PRODUCT_AVAILABILITIES,
            $availabilityEntityTransfer->getSku(),
            $restProductsConcreteAvailabilityAttributesTransfer
        );
    }
}
