<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Shipment\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Generated\Shared\Transfer\ShipmentCarrierTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Shipment\Business\ShipmentBusinessFactory getFactory()
 */
class ShipmentFacade extends AbstractFacade
{

    /**
     * @param ShipmentCarrierTransfer $carrierTransfer
     *
     * @return int
     */
    public function createCarrier(ShipmentCarrierTransfer $carrierTransfer)
    {
        $carrierModel = $this->getFactory()->createCarrier();

        return $carrierModel->create($carrierTransfer);
    }

    /**
     * @param ShipmentMethodTransfer $methodTransfer
     *
     * @return int
     */
    public function createMethod(ShipmentMethodTransfer $methodTransfer)
    {
        $methodModel = $this->getFactory()->createMethod();

        return $methodModel->create($methodTransfer);
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return ShipmentMethodsTransfer
     */
    public function getAvailableMethods(QuoteTransfer $quoteTransfer)
    {
        $methodModel = $this->getFactory()->createMethod();

        return $methodModel->getAvailableMethods($quoteTransfer);
    }

    /**
     * @param int $idMethod
     *
     * @return ShipmentMethodTransfer
     */
    public function getShipmentMethodTransferById($idMethod)
    {
        $methodModel = $this->getFactory()->createMethod();

        return $methodModel->getShipmentMethodTransferById($idMethod);
    }

    /**
     * @param int $idMethod
     *
     * @return bool
     */
    public function hasMethod($idMethod)
    {
        $methodModel = $this->getFactory()->createMethod();

        return $methodModel->hasMethod($idMethod);
    }

    /**
     * @param int $idMethod
     *
     * @return bool
     */
    public function deleteMethod($idMethod)
    {
        $methodModel = $this->getFactory()->createMethod();

        return $methodModel->deleteMethod($idMethod);
    }

    /**
     * @param ShipmentMethodTransfer $methodTransfer
     *
     * @return int
     */
    public function updateMethod(ShipmentMethodTransfer $methodTransfer)
    {
        $methodModel = $this->getFactory()->createMethod();

        return $methodModel->updateMethod($methodTransfer);
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     * @param CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveShipmentForOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $this->getFactory()->createShipmentOrderSaver()->saveShipmentForOrder($quoteTransfer, $checkoutResponse);
    }

}
