<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ShipmentFormTransfer;

class ShipmentFormCreateDataProvider
{
    /**
     * @var \Spryker\Zed\ShipmentGui\Communication\Form\DataProvider\ShipmentFormDefaultDataProvider
     */
    protected $shipmentFormDefaultDataProvider;

    /**
     * @param \Spryker\Zed\ShipmentGui\Communication\Form\DataProvider\ShipmentFormDefaultDataProvider $shipmentFormDefaultDataProvider
     */
    public function __construct(ShipmentFormDefaultDataProvider $shipmentFormDefaultDataProvider)
    {
        $this->shipmentFormDefaultDataProvider = $shipmentFormDefaultDataProvider;
    }

    /**
     * @param int $idSalesOrder
     * @param int|null $idSalesShipment
     *
     * @return \Generated\Shared\Transfer\ShipmentFormTransfer
     */
    public function getData(int $idSalesOrder, ?int $idSalesShipment = null): ShipmentFormTransfer
    {
        $formData = $this->shipmentFormDefaultDataProvider->getDefaultFormFields($idSalesOrder, $idSalesShipment);

        return $this->shipmentFormDefaultDataProvider->mapFormDataToShipmentFormTransfer($formData);
    }

    /**
     * @param int $idSalesOrder
     * @param int|null $idSalesShipment
     *
     * @return array[]
     */
    public function getOptions(int $idSalesOrder, ?int $idSalesShipment = null): array
    {
        return $this->shipmentFormDefaultDataProvider->getOptions($idSalesOrder, $idSalesShipment);
    }
}
