<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Persistence;

use Orm\Zed\Sales\Persistence\SpySalesShipment;

interface ShipmentGuiRepositoryInterface
{
    /**
     * @api
     *
     * @param int $idShipment
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesShipmentQuery
     */
    public function getShipmentById(int $idShipment): ?SpySalesShipment;
}
