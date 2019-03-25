<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Communication\Plugin;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Sales\Dependency\Plugin\HydrateOrderPluginInterface;

/**
 * @method \Spryker\Zed\Shipment\Business\ShipmentFacadeInterface getFacade()
 * @method \Spryker\Zed\Shipment\Communication\ShipmentCommunicationFactory getFactory()
 * @method \Spryker\Zed\Shipment\ShipmentConfig getConfig()
 * @method \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface getQueryContainer()
 */
class ShipmentOrderHydratePlugin extends AbstractPlugin implements HydrateOrderPluginInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrate(OrderTransfer $orderTransfer)
    {
        return $this->getFacade()->hydrateOrderShipment($orderTransfer);
    }
}
