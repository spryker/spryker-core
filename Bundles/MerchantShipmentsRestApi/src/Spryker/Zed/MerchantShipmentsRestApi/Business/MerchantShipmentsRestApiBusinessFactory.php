<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantShipmentsRestApi\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantShipmentsRestApi\Business\Expander\ShipmentExpander;
use Spryker\Zed\MerchantShipmentsRestApi\Business\Expander\ShipmentExpanderInterface;

/**
 * @method \Spryker\Zed\MerchantShipmentsRestApi\MerchantShipmentsRestApiConfig getConfig()
 */
class MerchantShipmentsRestApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantShipmentsRestApi\Business\Expander\ShipmentExpanderInterface
     */
    public function createShipmentExpander(): ShipmentExpanderInterface
    {
        return new ShipmentExpander();
    }
}
