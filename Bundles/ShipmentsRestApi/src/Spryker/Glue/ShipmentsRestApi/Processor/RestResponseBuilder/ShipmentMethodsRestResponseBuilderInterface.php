<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\RestShipmentMethodAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

interface ShipmentMethodsRestResponseBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestShipmentMethodAttributesTransfer $shipmentMethodTransfer
     * @param string $idShipmentMethod
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createShipmentMethodRestResource(
        RestShipmentMethodAttributesTransfer $shipmentMethodTransfer,
        string $idShipmentMethod
    ): RestResourceInterface;
}
