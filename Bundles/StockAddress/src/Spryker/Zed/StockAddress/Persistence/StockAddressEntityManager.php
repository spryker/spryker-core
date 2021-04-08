<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockAddress\Persistence;

use Generated\Shared\Transfer\StockAddressTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\StockAddress\Persistence\StockAddressPersistenceFactory getFactory()
 */
class StockAddressEntityManager extends AbstractEntityManager implements StockAddressEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\StockAddressTransfer $stockAddressTransfer
     *
     * @return \Generated\Shared\Transfer\StockAddressTransfer
     */
    public function saveStockAddress(StockAddressTransfer $stockAddressTransfer): StockAddressTransfer
    {
        $stockAddressMapper = $this->getFactory()->createStockAddressMapper();

        $stockAddressEntity = $this->getFactory()
            ->createStockAddressQuery()
            ->filterByFkStock($stockAddressTransfer->getIdStockOrFail())
            ->findOneOrCreate();

        $stockAddressEntity = $stockAddressMapper->mapStockAddressTransferToStockAddressEntity($stockAddressTransfer, $stockAddressEntity);
        $stockAddressEntity->save();

        return $stockAddressMapper->mapStockAddressEntityToStockAddressTransfer($stockAddressEntity, $stockAddressTransfer);
    }

    /**
     * @param int $idStock
     *
     * @return void
     */
    public function deleteStockAddressForStock(int $idStock): void
    {
        $stockAddressEntity = $this->getFactory()
            ->createStockAddressQuery()
            ->findOneByFkStock($idStock);

        if (!$stockAddressEntity) {
            return;
        }

        $stockAddressEntity->delete();
    }
}
