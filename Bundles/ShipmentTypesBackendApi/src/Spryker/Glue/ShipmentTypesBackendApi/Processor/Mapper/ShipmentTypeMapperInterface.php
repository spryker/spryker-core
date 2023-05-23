<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypesBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\ApiShipmentTypesAttributesTransfer;
use Generated\Shared\Transfer\GlueFilterTransfer;
use Generated\Shared\Transfer\ShipmentTypeConditionsTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;

interface ShipmentTypeMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     * @param \Generated\Shared\Transfer\ApiShipmentTypesAttributesTransfer $apiShipmentTypesAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ApiShipmentTypesAttributesTransfer
     */
    public function mapShipmentTypeTransferToApiShipmentTypesAttributesTransfer(
        ShipmentTypeTransfer $shipmentTypeTransfer,
        ApiShipmentTypesAttributesTransfer $apiShipmentTypesAttributesTransfer
    ): ApiShipmentTypesAttributesTransfer;

    /**
     * @param \Generated\Shared\Transfer\ApiShipmentTypesAttributesTransfer $apiShipmentTypesAttributesTransfer
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeTransfer
     */
    public function mapApiShipmentTypesAttributesTransferToShipmentTypeTransfer(
        ApiShipmentTypesAttributesTransfer $apiShipmentTypesAttributesTransfer,
        ShipmentTypeTransfer $shipmentTypeTransfer
    ): ShipmentTypeTransfer;

    /**
     * @param \Generated\Shared\Transfer\GlueFilterTransfer $glueFilterTransfer
     * @param \Generated\Shared\Transfer\ShipmentTypeConditionsTransfer $shipmentTypeConditionsTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeConditionsTransfer
     */
    public function mapGlueFilterTransferToShipmentTypeConditionsTransfer(
        GlueFilterTransfer $glueFilterTransfer,
        ShipmentTypeConditionsTransfer $shipmentTypeConditionsTransfer
    ): ShipmentTypeConditionsTransfer;
}
