<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Persistence;

use Orm\Zed\Sales\Persistence\SpySalesShipment;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\ShipmentGui\Persistence\ShipmentGuiRepositoryInterface;

/**
 * @method \Spryker\Zed\ShipmentGui\Persistence\ShipmentGuiPersistenceFactory getFactory()
 */
class ShipmentGuiRepository extends AbstractRepository implements ShipmentGuiRepositoryInterface
{
    public function getShipmentById(int $idShipment): ?SpySalesShipment
    {
        $salesShipment = $this->getFactory()
            ->createSpySalesShipmentQuery()
            ->findOneByIdSalesShipment($idShipment);

        return $salesShipment;
    }
}
