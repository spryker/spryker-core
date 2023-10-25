<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ShipmentTypeServicePointsRestApi;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class ShipmentTypeServicePointsRestApiConfig extends AbstractSharedConfig
{
    /**
     * Specification:
     * - Returns a list of shipment type keys which applicable for shipping address validation.
     *
     * @api
     *
     * @return list<string>
     */
    public function getApplicableShipmentTypeKeysForShippingAddress(): array
    {
        return [];
    }
}
