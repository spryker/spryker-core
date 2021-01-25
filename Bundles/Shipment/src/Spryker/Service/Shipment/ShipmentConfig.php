<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Shipment;

use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Service\Kernel\AbstractBundleConfig;

class ShipmentConfig extends AbstractBundleConfig
{
    /**
     * Returns array of field names for generation of hash for shipment.
     *
     * @api
     *
     * @return string[]
     */
    public function getShipmentHashFields(): array
    {
        return [];
    }

    /**
     * Returns an array of field names used to generate a hash for a shipment method.
     *
     * @api
     *
     * @return string[]
     */
    public function getShipmentMethodHashFields(): array
    {
        return [
            ShipmentMethodTransfer::NAME,
            ShipmentMethodTransfer::CARRIER_NAME,
        ];
    }
}
