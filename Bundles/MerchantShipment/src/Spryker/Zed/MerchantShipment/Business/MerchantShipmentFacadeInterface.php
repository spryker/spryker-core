<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantShipment\Business;

use Generated\Shared\Transfer\MerchantShipmentCriteriaTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;

interface MerchantShipmentFacadeInterface
{
    /**
     * Specification:
     * - Finds Shipment by provided params in MerchantShipmentCriteria.
     * - Returns Shipment transfer object.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantShipmentCriteriaTransfer $merchantShipmentCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer|null
     */
    public function findShipment(MerchantShipmentCriteriaTransfer $merchantShipmentCriteriaTransfer): ?ShipmentTransfer;
}
