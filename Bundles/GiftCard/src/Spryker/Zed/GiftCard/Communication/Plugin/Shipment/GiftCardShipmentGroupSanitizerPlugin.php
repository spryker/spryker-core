<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Communication\Plugin\Shipment;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentGroupsSanitizerPluginInterface;

/**
 * @method \Spryker\Zed\GiftCard\Business\GiftCardFacadeInterface getFacade()
 * @method \Spryker\Zed\GiftCard\GiftCardConfig getConfig()
 * @method \Spryker\Zed\GiftCard\Persistence\GiftCardQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\GiftCard\Communication\GiftCardCommunicationFactory getFactory()
 */
class GiftCardShipmentGroupSanitizerPlugin extends AbstractPlugin implements ShipmentGroupsSanitizerPluginInterface
{
    /**
     * Specification:
     *  - Sanitize shipment group collection.
     *
     * @api
     *
     * @param iterable|\Generated\Shared\Transfer\ShipmentGroupTransfer[] $shipmentGroupCollection
     *
     * @return iterable|\Generated\Shared\Transfer\ShipmentGroupTransfer[]
     */
    public function sanitizeShipmentGroupCollection(iterable $shipmentGroupCollection): iterable
    {
        return $this->getFacade()->sanitizeShipmentGroupCollection($shipmentGroupCollection);
    }
}
