<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Business;

use Generated\Shared\Shipment\ShipmentInterface;
use Generated\Shared\Shipment\ShipmentMethodAvailabilityInterface;
use Generated\Shared\Transfer\ShipmentCarrierTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Zed\Ide\FactoryAutoCompletion\ShipmentBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method ShipmentBusiness getFactory()
 * @method ShipmentDependencyContainer getDependencyContainer()
 */
class ShipmentFacade extends AbstractFacade
{

    /**
     * @param ShipmentCarrierTransfer $carrierTransfer
     *
     * @return ShipmentCarrierTransfer
     */
    public function createCarrier(ShipmentCarrierTransfer $carrierTransfer)
    {
        $carrierModel = $this->getDependencyContainer()
            ->createCarrier()
        ;

        return $carrierModel->create($carrierTransfer);
    }

    /**
     * @param ShipmentMethodTransfer $methodTransfer
     *
     * @return ShipmentCarrierTransfer
     */
    public function createMethod(ShipmentMethodTransfer $methodTransfer)
    {
        $methodModel = $this->getDependencyContainer()
            ->createMethod()
        ;

        return $methodModel->create($methodTransfer);
    }

    /**
     * @param ShipmentMethodAvailabilityInterface $shipmentMethodAvailability
     *
     * @return ShipmentInterface
     */
    public function getAvailableMethods(ShipmentMethodAvailabilityInterface $shipmentMethodAvailability)
    {
        $methodModel = $this->getDependencyContainer()
            ->createMethod()
        ;

        return $methodModel->getAvailableMethods($shipmentMethodAvailability);
    }

    /**
     * @param $idMethod
     *
     * @return ShipmentMethodTransfer
     */
    public function getShipmentMethodTransferById($idMethod)
    {
        $methodModel = $this->getDependencyContainer()
            ->createMethod()
        ;

        return $methodModel->getShipmentMethodTransferById($idMethod);
    }

    /**
     * @param int $idMethod
     *
     * @return bool
     */
    public function hasMethod($idMethod)
    {
        $methodModel = $this->getDependencyContainer()
            ->createMethod()
        ;

        return $methodModel->hasMethod($idMethod);
    }

    /**
     * @param int $idMethod
     *
     * @return bool
     */
    public function deleteMethod($idMethod)
    {
        $methodModel = $this->getDependencyContainer()
            ->createMethod()
        ;

        return $methodModel->deleteMethod($idMethod);
    }

    /**
     * @param ShipmentMethodTransfer $methodTransfer
     *
     * @return ShipmentCarrierTransfer
     */
    public function updateMethod(ShipmentMethodTransfer $methodTransfer)
    {
        $methodModel = $this->getDependencyContainer()
            ->createMethod()
        ;

        return $methodModel->updateMethod($methodTransfer);
    }

}
