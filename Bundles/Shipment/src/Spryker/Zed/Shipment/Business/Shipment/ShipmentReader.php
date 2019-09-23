<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Shipment;

use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToSalesFacadeInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface;

class ShipmentReader implements ShipmentReaderInterface
{
    /**
     * @var \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToSalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface
     */
    protected $shipmentRepository;

    /**
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToSalesFacadeInterface $salesFacade
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface $shipmentRepository
     */
    public function __construct(
        ShipmentToSalesFacadeInterface $salesFacade,
        ShipmentRepositoryInterface $shipmentRepository
    ) {
        $this->salesFacade = $salesFacade;
        $this->shipmentRepository = $shipmentRepository;
    }

    /**
     * @param int $idSalesShipment
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer|null
     */
    public function findShipmentById(int $idSalesShipment): ?ShipmentTransfer
    {
        $shipmentTransfer = $this->shipmentRepository->findShipmentById($idSalesShipment);
        if ($shipmentTransfer === null) {
            return null;
        }

        $shipmentMethodTransfer = $shipmentTransfer->getMethod();
        if ($shipmentMethodTransfer === null) {
            return $shipmentTransfer;
        }

        $shipmentMethodTransfer = $this->getShipmentMethodTransferByName($shipmentMethodTransfer->getName());
        $shipmentTransfer->setMethod($shipmentMethodTransfer);

        return $shipmentTransfer;
    }

    /**
     * @param string $shipmentMethodName
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    protected function getShipmentMethodTransferByName(string $shipmentMethodName): ?ShipmentMethodTransfer
    {
        if ($shipmentMethodName === '') {
            return null;
        }

        return $this->shipmentRepository->findShipmentMethodByName($shipmentMethodName);
    }
}
