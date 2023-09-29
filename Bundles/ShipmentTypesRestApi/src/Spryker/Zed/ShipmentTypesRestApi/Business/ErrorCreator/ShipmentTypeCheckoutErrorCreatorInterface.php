<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypesRestApi\Business\ErrorCreator;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;

interface ShipmentTypeCheckoutErrorCreatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutErrorTransfer
     */
    public function createShipmentTypeNotAvailableCheckoutErrorTransfer(ShipmentTypeTransfer $shipmentTypeTransfer): CheckoutErrorTransfer;
}
