<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentDiscountConnector\Dependency\Facade;

interface ShipmentDiscountConnectorToShipmentInterface
{
    /**
     * @return \Generated\Shared\Transfer\ShipmentCarrierTransfer[]
     */
    public function getCarriers();

    /**
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer[]
     */
    public function getMethods();

    /**
     * @param int $idShipmentMethod
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    public function findMethodById($idShipmentMethod);
}
