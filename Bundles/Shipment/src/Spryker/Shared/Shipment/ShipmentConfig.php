<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Shipment;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class ShipmentConfig extends AbstractSharedConfig
{
    /**
     * @var string
     */
    public const SHIPMENT_METHOD_NAME_NO_SHIPMENT = 'NoShipment';

    /**
     * Specification:
     * - Shipment expense type name.
     *
     * @api
     *
     * @var string
     */
    public const SHIPMENT_EXPENSE_TYPE = 'SHIPMENT_EXPENSE_TYPE';

    /**
     * @api
     *
     * @return bool
     */
    public function isMultiShipmentSelectionEnabled(): bool
    {
        return false;
    }
}
