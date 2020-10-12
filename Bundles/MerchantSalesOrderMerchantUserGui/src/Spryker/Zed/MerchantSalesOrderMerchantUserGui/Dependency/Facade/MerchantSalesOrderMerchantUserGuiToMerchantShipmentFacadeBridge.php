<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderMerchantUserGui\Dependency\Facade;

use Generated\Shared\Transfer\ShipmentTransfer;

class MerchantSalesOrderMerchantUserGuiToMerchantShipmentFacadeBridge implements MerchantSalesOrderMerchantUserGuiToMerchantShipmentFacadeInterface
{
    /**
     * @var \Spryker\Zed\MerchantShipment\Business\MerchantShipmentFacadeInterface
     */
    protected $merchantShipmentFacade;

    /**
     * @param \Spryker\Zed\MerchantShipment\Business\MerchantShipmentFacadeInterface $merchantShipmentFacade
     */
    public function __construct($merchantShipmentFacade)
    {
        $this->merchantShipmentFacade = $merchantShipmentFacade;
    }

    /**
     * @param string $merchantReference
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return bool
     */
    public function isMerchantOrderShipment(
        string $merchantReference,
        ShipmentTransfer $shipmentTransfer
    ): bool {
        return $this->merchantShipmentFacade->isMerchantOrderShipment($merchantReference, $shipmentTransfer);
    }
}
