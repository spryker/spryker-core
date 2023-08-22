<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypesBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\GlueFilterTransfer;
use Generated\Shared\Transfer\ShipmentTypeConditionsTransfer;
use Generated\Shared\Transfer\ShipmentTypesBackendApiAttributesTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;

interface ShipmentTypeMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     * @param \Generated\Shared\Transfer\ShipmentTypesBackendApiAttributesTransfer $shipmentTypesBackendApiAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypesBackendApiAttributesTransfer
     */
    public function mapShipmentTypeTransferToShipmentTypesBackendApiAttributesTransfer(
        ShipmentTypeTransfer $shipmentTypeTransfer,
        ShipmentTypesBackendApiAttributesTransfer $shipmentTypesBackendApiAttributesTransfer
    ): ShipmentTypesBackendApiAttributesTransfer;

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypesBackendApiAttributesTransfer $shipmentTypesBackendApiAttributesTransfer
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeTransfer
     */
    public function mapShipmentTypesBackendApiAttributesTransferToShipmentTypeTransfer(
        ShipmentTypesBackendApiAttributesTransfer $shipmentTypesBackendApiAttributesTransfer,
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
