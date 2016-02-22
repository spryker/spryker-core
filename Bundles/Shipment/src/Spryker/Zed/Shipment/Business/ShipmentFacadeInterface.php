<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business;

use Generated\Shared\Transfer\ShipmentCarrierTransfer;
use Generated\Shared\Transfer\ShipmentMethodAvailabilityTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;

interface ShipmentFacadeInterface
{

    /**
     * @param \Generated\Shared\Transfer\ShipmentCarrierTransfer $carrierTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentCarrierTransfer
     */
    public function createCarrier(ShipmentCarrierTransfer $carrierTransfer);

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $methodTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentCarrierTransfer
     */
    public function createMethod(ShipmentMethodTransfer $methodTransfer);

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodAvailabilityTransfer $shipmentMethodAvailabilityTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    public function getAvailableMethods(ShipmentMethodAvailabilityTransfer $shipmentMethodAvailabilityTransfer);

    /**
     * @param int $idMethod
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    public function getShipmentMethodTransferById($idMethod);

    /**
     * @param int $idMethod
     *
     * @return bool
     */
    public function hasMethod($idMethod);

    /**
     * @param int $idMethod
     *
     * @return bool
     */
    public function deleteMethod($idMethod);

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $methodTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentCarrierTransfer
     */
    public function updateMethod(ShipmentMethodTransfer $methodTransfer);

}
