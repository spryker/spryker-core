<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ShipmentCarrierTransfer;
use Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToShipmentFacadeInterface;

class ShipmentCarrierFormDataProvider
{
    /**
     * @var \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToShipmentFacadeInterface
     */
    protected $shipmentFacade;

    /**
     * @param \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToShipmentFacadeInterface $shipmentFacade
     */
    public function __construct(ShipmentGuiToShipmentFacadeInterface $shipmentFacade)
    {
        $this->shipmentFacade = $shipmentFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentCarrierTransfer $shipmentCarrierTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentCarrierTransfer
     */
    public function getData(ShipmentCarrierTransfer $shipmentCarrierTransfer): ShipmentCarrierTransfer
    {
        if ($shipmentCarrierTransfer->getIdShipmentCarrier() === null) {
            return $shipmentCarrierTransfer;
        }

        $foundShipmentCarrierTransfer = $this->shipmentFacade->findShipmentCarrierById($shipmentCarrierTransfer->getIdShipmentCarrier());
        if ($foundShipmentCarrierTransfer === null) {
            return $shipmentCarrierTransfer;
        }

        return $foundShipmentCarrierTransfer;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            'data_class' => ShipmentCarrierTransfer::class,
        ];
    }
}
