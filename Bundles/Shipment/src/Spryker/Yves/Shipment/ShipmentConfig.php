<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Shipment;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class ShipmentConfig extends AbstractBundleConfig
{
    /**
     * @example 'No shipment'
     *
     * @deprecated Unused, will be removed with next major release. Use \Spryker\Shared\Shipment\ShipmentConfig::SHIPMENT_METHOD_NAME_NO_SHIPMENT instead.
     *
     * Specification:
     * - Defines a name of a shipment method, which will be used for orders without selected shipment
     * - The defined shipment method name MUST exist in your DB
     *
     * @return string
     */
    public function getNoShipmentMethodName()
    {
        return '';
    }
}
