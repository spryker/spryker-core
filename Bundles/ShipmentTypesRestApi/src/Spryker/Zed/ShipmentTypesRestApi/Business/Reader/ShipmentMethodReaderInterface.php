<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypesRestApi\Business\Reader;

interface ShipmentMethodReaderInterface
{
    /**
     * @return array<int, \Generated\Shared\Transfer\ShipmentMethodTransfer>
     */
    public function getShipmentMethodTransfersIndexedByIdShipmentMethod(): array;
}
