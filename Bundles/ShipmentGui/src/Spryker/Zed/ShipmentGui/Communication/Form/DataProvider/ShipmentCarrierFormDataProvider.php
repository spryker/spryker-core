<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form\DataProvider;

use Spryker\Zed\ShipmentGui\Communication\Form\ShipmentCarrier\ShipmentCarrierFormType;
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
     * @param int|null $idCarrier
     *
     * @return bool[]
     */
    public function getData(?int $idCarrier = null): array
    {
        if ($idCarrier === null) {
            return [];
        }

        return [
            ShipmentCarrierFormType::FIELD_IS_ACTIVE_FIELD => $this->isCarrierActive($idCarrier),
        ];
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [];
    }

    /**
     * @param int $idCarrier
     *
     * @return bool
     */
    protected function isCarrierActive(int $idCarrier): bool
    {
        $shipmentCarrierTransfer = $this->shipmentFacade->findShipmentCarrierById($idCarrier);
        if ($shipmentCarrierTransfer === null || $shipmentCarrierTransfer->getIsActive() === null) {
            return false;
        }

        return $shipmentCarrierTransfer->getIsActive();
    }
}
