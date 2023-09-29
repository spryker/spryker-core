<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypesRestApi\Dependency\Facade;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodsCollectionTransfer;

class ShipmentTypesRestApiToShipmentFacadeBridge implements ShipmentTypesRestApiToShipmentFacadeInterface
{
    /**
     * @var \Spryker\Zed\Shipment\Business\ShipmentFacadeInterface
     */
    protected $shipmentFacade;

    /**
     * @param \Spryker\Zed\Shipment\Business\ShipmentFacadeInterface $shipmentFacade
     */
    public function __construct($shipmentFacade)
    {
        $this->shipmentFacade = $shipmentFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsCollectionTransfer
     */
    public function getAvailableMethodsByShipment(QuoteTransfer $quoteTransfer): ShipmentMethodsCollectionTransfer
    {
        return $this->shipmentFacade->getAvailableMethodsByShipment($quoteTransfer);
    }

    /**
     * @return list<\Generated\Shared\Transfer\ShipmentMethodTransfer>
     */
    public function getMethods(): array
    {
        return $this->shipmentFacade->getMethods();
    }
}
