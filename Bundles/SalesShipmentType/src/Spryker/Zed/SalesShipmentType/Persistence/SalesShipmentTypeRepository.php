<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesShipmentType\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\SalesShipmentType\Persistence\SalesShipmentTypePersistenceFactory getFactory()
 */
class SalesShipmentTypeRepository extends AbstractRepository implements SalesShipmentTypeRepositoryInterface
{
    /**
     * @param list<string> $salesShipmentTypeKeys
     *
     * @return list<\Generated\Shared\Transfer\SalesShipmentTypeTransfer>
     */
    public function getSalesShipmentTypesByKeys(array $salesShipmentTypeKeys): array
    {
        $salesShipmentTypeEntities = $this->getFactory()
            ->createSalesShipmentTypeQuery()
            ->filterByKey_In($salesShipmentTypeKeys)
            ->find();

        if ($salesShipmentTypeEntities->count() === 0) {
            return [];
        }

        return $this->getFactory()
            ->createSalesShipmentTypeMapper()
            ->mapSalesShipmentTypeEntitiesToSalesShipmentTypeTransfers(
                $salesShipmentTypeEntities,
                [],
            );
    }
}
