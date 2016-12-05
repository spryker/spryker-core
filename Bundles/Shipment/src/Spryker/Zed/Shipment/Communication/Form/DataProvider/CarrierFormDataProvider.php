<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Communication\Form\DataProvider;

use Spryker\Zed\Shipment\Communication\Form\CarrierForm;
use Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface;

class CarrierFormDataProvider
{

    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface
     */
    protected $shipmentQueryContainer;

    /**
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface $shipmentQueryContainer
     */
    public function __construct(ShipmentQueryContainerInterface $shipmentQueryContainer)
    {
        $this->shipmentQueryContainer = $shipmentQueryContainer;
    }

    /**
     * @param int|null $idCarrier
     *
     * @return array
     */
    public function getData($idCarrier = null)
    {
        $result = [];

        if (!$idCarrier) {
            return $result;
        }

        $result = [
            CarrierForm::FIELD_IS_ACTIVE_FIELD => $this->getCarrierIsActive($idCarrier),
        ];

        return $result;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [];
    }

    /**
     * @param int $idCarrier
     *
     * @return bool
     */
    protected function getCarrierIsActive($idCarrier)
    {
        $carrier = $this->shipmentQueryContainer
            ->queryCarriers()
            ->findOneByIdShipmentCarrier($idCarrier);

        return $carrier->getIsActive();
    }

}
