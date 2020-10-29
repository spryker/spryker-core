<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\RestShipmentsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

interface ShipmentRestResponseBuilderInterface
{
    /**
     * @param string $shipmentHash
     * @param \Generated\Shared\Transfer\RestShipmentsAttributesTransfer $restShipmentsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createShipmentRestResource(
        string $shipmentHash,
        RestShipmentsAttributesTransfer $restShipmentsAttributesTransfer
    ): RestResourceInterface;
}
