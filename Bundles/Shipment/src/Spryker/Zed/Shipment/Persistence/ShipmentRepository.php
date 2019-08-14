<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Shipment\Persistence\ShipmentPersistenceFactory getFactory()
 */
class ShipmentRepository extends AbstractRepository implements ShipmentRepositoryInterface
{
    /**
     * @param string $methodName
     * @param int $idMethod
     * @param int $idCarrier
     *
     * @return bool
     */
    public function hasMethodByNameAndIdCarrier(string $methodName, int $idMethod, int $idCarrier): bool
    {
        $count = $this->getFactory()
            ->createShipmentMethodQuery()
            ->filterByName($methodName)
            ->filterByIdShipmentMethod($idMethod, Criteria::NOT_EQUAL)
            ->filterByFkShipmentCarrier($idCarrier)
            ->count();

        return (bool)$count;
    }
}
