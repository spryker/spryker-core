<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStock\Persistence\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\MerchantStockTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Orm\Zed\MerchantStock\Persistence\SpyMerchantStock;
use Orm\Zed\Stock\Persistence\SpyStock;
use Propel\Runtime\Collection\Collection;

class MerchantStockMapper
{
    /**
     * @var \Spryker\Zed\MerchantStock\Persistence\Mapper\StockStoreRelationMapper
     */
    protected StockStoreRelationMapper $stockStoreRelationMapper;

    /**
     * @param \Spryker\Zed\MerchantStock\Persistence\Mapper\StockStoreRelationMapper $stockStoreRelationMapper
     */
    public function __construct(StockStoreRelationMapper $stockStoreRelationMapper)
    {
        $this->stockStoreRelationMapper = $stockStoreRelationMapper;
    }

    /**
     * @param \Orm\Zed\Stock\Persistence\SpyStock $stockEntity
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return \Generated\Shared\Transfer\StockTransfer
     */
    public function mapStockEntityToStockTransfer(
        SpyStock $stockEntity,
        StockTransfer $stockTransfer
    ): StockTransfer {
        $stockTransfer->fromArray($stockEntity->toArray(), true);
        $stockTransfer->setStoreRelation(
            $this->stockStoreRelationMapper->mapStockStoreEntitiesToStoreRelationTransfer(
                $stockEntity->getIdStock(),
                $stockEntity->getStockStores()->getArrayCopy(),
                new StoreRelationTransfer(),
            ),
        );

        return $stockTransfer;
    }

    /**
     * @param \Orm\Zed\MerchantStock\Persistence\SpyMerchantStock $merchantStockEntity
     * @param \Generated\Shared\Transfer\MerchantStockTransfer $merchantStockTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantStockTransfer
     */
    public function mapMerchantStockEntityToMerchantStockTransfer(
        SpyMerchantStock $merchantStockEntity,
        MerchantStockTransfer $merchantStockTransfer
    ): MerchantStockTransfer {
        return $merchantStockTransfer->setIdMerchantStock($merchantStockEntity->getIdMerchantStock())
            ->setIdMerchant($merchantStockEntity->getFkMerchant())
            ->setIdStock($merchantStockEntity->getFkStock())
            ->setIsDefault($merchantStockEntity->getIsDefault());
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantStockTransfer $merchantStockTransfer
     * @param \Orm\Zed\MerchantStock\Persistence\SpyMerchantStock $merchantStockEntity
     *
     * @return \Orm\Zed\MerchantStock\Persistence\SpyMerchantStock
     */
    public function mapMerchantStockTransferToMerchantStockEntity(
        MerchantStockTransfer $merchantStockTransfer,
        SpyMerchantStock $merchantStockEntity
    ): SpyMerchantStock {
        return $merchantStockEntity->setIdMerchantStock($merchantStockTransfer->getIdMerchantStock())
            ->setFkMerchant($merchantStockTransfer->getIdMerchant())
            ->setFkStock($merchantStockTransfer->getIdStock())
            ->setIsDefault($merchantStockTransfer->getIsDefault());
    }

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\MerchantStock\Persistence\SpyMerchantStock> $merchantStocksEntities
     *
     * @return array<int, \ArrayObject<int, \Generated\Shared\Transfer\StockTransfer>>
     */
    public function mapMerchantStockEntityCollectionToStocksGroupedByIdMerchant(Collection $merchantStocksEntities): array
    {
        $stockTransfersGroupedByIdMerchant = [];

        foreach ($merchantStocksEntities as $merchantStockEntity) {
            $idMerchant = $merchantStockEntity->getFkMerchant();

            if (!isset($stockTransfersGroupedByIdMerchant[$idMerchant])) {
                $stockTransfersGroupedByIdMerchant[$idMerchant] = new ArrayObject();
            }

            $stockTransfersGroupedByIdMerchant[$idMerchant]->append(
                $this->mapStockEntityToStockTransfer($merchantStockEntity->getSpyStock(), new StockTransfer()),
            );
        }

        return $stockTransfersGroupedByIdMerchant;
    }
}
