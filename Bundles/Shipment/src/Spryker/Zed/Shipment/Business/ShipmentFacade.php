<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentCarrierTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Shipment\Business\ShipmentBusinessFactory getFactory()
 */
class ShipmentFacade extends AbstractFacade implements ShipmentFacadeInterface
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ShipmentCarrierTransfer $carrierTransfer
     *
     * @return int
     */
    public function createCarrier(ShipmentCarrierTransfer $carrierTransfer)
    {
        $carrierModel = $this->getFactory()->createCarrier();

        return $carrierModel->create($carrierTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return ShipmentCarrierTransfer[]
     */
    public function findCarriers()
    {
        return $this->getFactory()
            ->createShipmentCarrierReader()
            ->findCarriers();
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $methodTransfer
     *
     * @return int
     */
    public function createMethod(ShipmentMethodTransfer $methodTransfer)
    {
        $methodModel = $this->getFactory()->createMethod();

        return $methodModel->create($methodTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idShipmentMethod
     *
     * @return ShipmentMethodTransfer|null
     */
    public function findMethodById($idShipmentMethod)
    {
        return $this->getFactory()
            ->createMethod()
            ->findShipmentMethodTransferById($idShipmentMethod);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsTransfer
     */
    public function getAvailableMethods(QuoteTransfer $quoteTransfer)
    {
        $methodModel = $this->getFactory()->createMethod();

        return $methodModel->getAvailableMethods($quoteTransfer);
    }

    /**
     * @api
     *
     * @param int $idMethod
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    public function getShipmentMethodTransferById($idMethod)
    {
        $methodModel = $this->getFactory()->createMethod();

        return $methodModel->getShipmentMethodTransferById($idMethod);
    }

    /**
     * @api
     *
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
     * @api
     *
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
     * @api
     *
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $methodTransfer
     *
     * @return int
     */
    public function updateMethod(ShipmentMethodTransfer $methodTransfer)
    {
        $methodModel = $this->getFactory()->createMethod();

        return $methodModel->updateMethod($methodTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function calculateShipmentTaxRate(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()->createShipmentTaxCalculator()->recalculate($quoteTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveShipmentForOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $this->getFactory()->createShipmentOrderSaver()->saveShipmentForOrder($quoteTransfer, $checkoutResponse);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateOrderShipment(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()->createShipmentOrderHydrate()->hydrateOrderWithShipment($orderTransfer);
    }

}
