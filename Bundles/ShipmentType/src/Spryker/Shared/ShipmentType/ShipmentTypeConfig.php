<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ShipmentType;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class ShipmentTypeConfig extends AbstractSharedConfig
{
    /**
     * @api
     *
     * @var string
     */
    public const SHIPMENT_TYPE_DELIVERY = 'delivery';

    /**
     * @api
     *
     * @var string
     */
    public const SHIPMENT_TYPE_PICKUP = 'pickup';
}
