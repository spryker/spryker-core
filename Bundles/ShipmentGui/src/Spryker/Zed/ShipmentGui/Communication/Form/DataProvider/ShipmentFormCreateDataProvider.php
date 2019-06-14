<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form\DataProvider;

class ShipmentFormCreateDataProvider implements ShipmentFormDataProviderInterface
{
    /**
     * @var \Spryker\Zed\ShipmentGui\Communication\Form\DataProvider\ShipmentFormDefaultDataProviderInterface
     */
    protected $shipmentFormDefaultDataProvider;

    /**
     * @param \Spryker\Zed\ShipmentGui\Communication\Form\DataProvider\ShipmentFormDefaultDataProviderInterface $shipmentFormDefaultDataProvider
     */
    public function __construct(ShipmentFormDefaultDataProviderInterface $shipmentFormDefaultDataProvider)
    {
        $this->shipmentFormDefaultDataProvider = $shipmentFormDefaultDataProvider;
    }

    /**
     * @param int $idSalesOrder
     * @param int|null $idSalesShipment
     *
     * @return array
     */
    public function getData(int $idSalesOrder, ?int $idSalesShipment = null): array
    {
        return $this->shipmentFormDefaultDataProvider->getDefaultFormFields($idSalesOrder, $idSalesShipment);
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
