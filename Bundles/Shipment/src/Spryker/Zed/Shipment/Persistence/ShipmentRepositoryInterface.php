<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Persistence;

interface ShipmentRepositoryInterface
{
    /**
     * @param string $methodName
     * @param int $idMethod
     * @param int $idCarrier
     *
     * @return bool
     */
    public function hasMethodByNameAndIdCarrier(string $methodName, int $idMethod, int $idCarrier): bool;
}
