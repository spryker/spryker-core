<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ShipmentCarrierRequestTransfer;
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
     * @param int|null $idShipmentCarrier
     *
     * @return \Generated\Shared\Transfer\ShipmentCarrierTransfer
     */
    public function getData(?int $idShipmentCarrier = null): ShipmentCarrierTransfer
    {
        if ($idShipmentCarrier === null) {
            return new ShipmentCarrierTransfer();
        }

        $shipmentCarrierRequestTransfer = (new ShipmentCarrierRequestTransfer())->setIdCarrier($idShipmentCarrier);

        return $this->shipmentFacade->findShipmentCarrier($shipmentCarrierRequestTransfer) ?? new ShipmentCarrierTransfer();
    }

    /**
     * @return string[]
     */
    public function getOptions(): array
    {
        return [
            'data_class' => ShipmentCarrierTransfer::class,
        ];
    }
}
