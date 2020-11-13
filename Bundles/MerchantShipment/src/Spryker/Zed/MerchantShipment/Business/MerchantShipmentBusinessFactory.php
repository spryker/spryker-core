<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantShipment\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantShipment\Business\Expander\ShipmentExpander;
use Spryker\Zed\MerchantShipment\Business\Expander\ShipmentExpanderInterface;

/**
 * @method \Spryker\Zed\MerchantShipment\MerchantShipmentConfig getConfig()
 */
class MerchantShipmentBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantShipment\Business\Expander\ShipmentExpanderInterface
     */
    public function createShipmentExpander(): ShipmentExpanderInterface
    {
        return new ShipmentExpander();
    }
}
