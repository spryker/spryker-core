<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAvailabilitiesRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestAbstractProductAvailabilityAttributesTransfer;
use Generated\Shared\Transfer\SpyAvailabilityAbstractEntityTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;

class AbstractProductAvailabilitiesResourceMapper implements AbstractProductAvailabilitiesResourceMapperInterface
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
     * @param \Generated\Shared\Transfer\SpyAvailabilityAbstractEntityTransfer $availabilityEntityTransfer
     *
     * @return \Generated\Shared\Transfer\RestAbstractProductAvailabilityAttributesTransfer
     */
    public function mapAvailabilityTransferToRestAbstractProductAvailabilityAttributesTransfer(SpyAvailabilityAbstractEntityTransfer $availabilityEntityTransfer): RestAbstractProductAvailabilityAttributesTransfer
    {
        $restProductsAbstractAvailabilityAttributesTransfer = (new RestAbstractProductAvailabilityAttributesTransfer())
            ->fromArray($availabilityEntityTransfer->toArray(true), true);
        $restProductsAbstractAvailabilityAttributesTransfer->setAvailability($availabilityEntityTransfer->getQuantity() > 0);

        return $restProductsAbstractAvailabilityAttributesTransfer;
    }
}
