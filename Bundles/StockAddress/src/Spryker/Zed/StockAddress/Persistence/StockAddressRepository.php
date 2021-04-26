<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockAddress\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\StockAddress\Persistence\StockAddressPersistenceFactory getFactory()
 */
class StockAddressRepository extends AbstractRepository implements StockAddressRepositoryInterface
{
    /**
     * @param int[] $stockIds
     *
     * @return \Generated\Shared\Transfer\StockAddressTransfer[]
     */
    public function getStockAddressesByStockIds(array $stockIds): array
    {
        $stockAddressEntities = $this->getFactory()
            ->createStockAddressQuery()
            ->filterByFkStock_In($stockIds)
            ->find();

        if ($stockAddressEntities->count() === 0) {
            return [];
        }

        return $this->getFactory()
            ->createStockAddressMapper()
            ->mapStockAddressEntitiesToStockAddressTransfers($stockAddressEntities, []);
    }

    /**
     * @param int $idStock
     *
     * @return bool
     */
    public function isStockAddressExistsForStock(int $idStock): bool
    {
        return $this->getFactory()
            ->createStockAddressQuery()
            ->filterByFkStock($idStock)
            ->exists();
    }
}
