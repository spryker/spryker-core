<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\ShipmentMethod;

use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Zed\Shipment\Business\Model\MethodPriceInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface;

/**
 * @method \Spryker\Zed\Shipment\Persistence\ShipmentPersistenceFactory getFactory()
 */
class MethodWriter implements MethodWriterInterface
{
    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface
     */
    protected $shipmentRepository;

    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface
     */
    protected $shipmentEntityManager;

    /**
     * @var \Spryker\Zed\Shipment\Business\Model\MethodPriceInterface
     */
    protected $methodPrice;

    /**
     * @var \Spryker\Zed\Shipment\Business\ShipmentMethod\MethodReaderInterface
     */
    protected $methodReader;

    /**
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface $shipmentRepository
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface $shipmentEntityManager
     * @param \Spryker\Zed\Shipment\Business\ShipmentMethod\MethodReaderInterface $methodReader
     * @param \Spryker\Zed\Shipment\Business\Model\MethodPriceInterface $methodPrice
     */
    public function __construct(
        ShipmentRepositoryInterface $shipmentRepository,
        ShipmentEntityManagerInterface $shipmentEntityManager,
        MethodReaderInterface $methodReader,
        MethodPriceInterface $methodPrice
    ) {
        $this->shipmentRepository = $shipmentRepository;
        $this->shipmentEntityManager = $shipmentEntityManager;
        $this->methodPrice = $methodPrice;
        $this->methodReader = $methodReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return int|null
     */
    public function create(ShipmentMethodTransfer $shipmentMethodTransfer): ?int
    {
        $shipmentMethodTransfer = $this->shipmentEntityManager->saveSalesShipmentMethod($shipmentMethodTransfer);
        $this->methodPrice->save($shipmentMethodTransfer);

        return $shipmentMethodTransfer->getIdShipmentMethod();
    }

    /**
     * @param int $idShipmentMethod
     *
     * @return bool
     */
    public function delete(int $idShipmentMethod): bool
    {
        $this->shipmentEntityManager->deleteMethodByIdMethod($idShipmentMethod);

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return bool
     */
    public function update(ShipmentMethodTransfer $shipmentMethodTransfer): bool
    {
        $shipmentMethodTransfer = $this->shipmentEntityManager->saveSalesShipmentMethod($shipmentMethodTransfer);
        $this->methodPrice->save($shipmentMethodTransfer);

        return true;
    }
}
