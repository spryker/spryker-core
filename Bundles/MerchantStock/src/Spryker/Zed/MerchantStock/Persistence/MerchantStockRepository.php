<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStock\Persistence;

use Generated\Shared\Transfer\MerchantStockCriteriaTransfer;
use Generated\Shared\Transfer\StockCollectionTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\MerchantStock\Persistence\Exception\DefaultMerchantStockNotFoundException;

/**
 * @method \Spryker\Zed\MerchantStock\Persistence\MerchantStockPersistenceFactory getFactory()
 */
class MerchantStockRepository extends AbstractRepository implements MerchantStockRepositoryInterface
{
    /**
     * @module Stock
     *
     * @param \Generated\Shared\Transfer\MerchantStockCriteriaTransfer $merchantStockCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\StockCollectionTransfer
     */
    public function get(MerchantStockCriteriaTransfer $merchantStockCriteriaTransfer): StockCollectionTransfer
    {
        $merchantStocksEntities = $this->getFactory()
            ->createMerchantStockPropelQuery()
            ->leftJoinWithSpyStock()
            ->filterByFkMerchant($merchantStockCriteriaTransfer->requireIdMerchant()->getIdMerchant())
            ->find();

        $stockCollectionTransfer = new StockCollectionTransfer();
        $merchantStockMapper = $this->getFactory()->createMerchantStockMapper();

        foreach ($merchantStocksEntities as $merchantStockEntity) {
            $stockCollectionTransfer->addStock(
                $merchantStockMapper->mapStockEntityToStockTransfer($merchantStockEntity->getSpyStock(), new StockTransfer())
            );
        }

        return $stockCollectionTransfer;
    }

    /**
     * @param int $idMerchant
     *
     * @throws \Spryker\Zed\MerchantStock\Persistence\Exception\DefaultMerchantStockNotFoundException
     *
     * @return \Generated\Shared\Transfer\StockTransfer
     */
    public function getDefaultMerchantStock(int $idMerchant): StockTransfer
    {
        $merchantStockEntity = $this->getFactory()
            ->createMerchantStockPropelQuery()
            ->leftJoinWithSpyStock()
            ->filterByFkMerchant($idMerchant)
            ->filterByIsDefault(true)
            ->findOne();

        if (!$merchantStockEntity) {
            throw new DefaultMerchantStockNotFoundException(sprintf(
                'Default Merchant stock not found by Merchant ID `%s`',
                $idMerchant
            ));
        }

        return $this->getFactory()->createMerchantStockMapper()->mapStockEntityToStockTransfer(
            $merchantStockEntity->getSpyStock(),
            new StockTransfer()
        );
    }
}
