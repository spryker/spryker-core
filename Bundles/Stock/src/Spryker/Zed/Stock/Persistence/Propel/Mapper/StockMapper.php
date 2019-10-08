<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Orm\Zed\Stock\Persistence\SpyStock;

class StockMapper
{
    /**
     * @var \Spryker\Zed\Stock\Persistence\Propel\Mapper\StockStoreRelationMapper
     */
    protected $stockStoreRelationMapper;

    /**
     * @param \Spryker\Zed\Stock\Persistence\Propel\Mapper\StockStoreRelationMapper $stockStoreRelationMapper
     */
    public function __construct(StockStoreRelationMapper $stockStoreRelationMapper)
    {
        $this->stockStoreRelationMapper = $stockStoreRelationMapper;
    }

    /**
     * @param \Orm\Zed\Stock\Persistence\SpyStock[] $stockEntities
     *
     * @return \Generated\Shared\Transfer\StockTransfer[]
     */
    public function mapStockEntitiesToStockTransfers(array $stockEntities): array
    {
        $stockTransfers = [];
        foreach ($stockEntities as $stockEntity) {
            $stockTransfers[] = $this->mapStockEntityToStockTransfer($stockEntity, new StockTransfer());
        }

        return $stockTransfers;
    }

    /**
     * @param \Orm\Zed\Stock\Persistence\SpyStock $stockEntity
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return \Generated\Shared\Transfer\StockTransfer
     */
    public function mapStockEntityToStockTransfer(SpyStock $stockEntity, StockTransfer $stockTransfer): StockTransfer
    {
        $stockTransfer->fromArray($stockEntity->toArray(), true);
        $stockTransfer->setStoreRelation(
            $this->stockStoreRelationMapper->mapStockStoreEntityToStoreRelationTransfer($stockEntity, new StoreRelationTransfer())
        );

        return $stockTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     * @param \Orm\Zed\Stock\Persistence\SpyStock $stockEntity
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStock
     */
    public function mapStockTransferToStockEntity(StockTransfer $stockTransfer, SpyStock $stockEntity): SpyStock
    {
        $stockEntity->fromArray($stockTransfer->modifiedToArray());

        return $stockEntity;
    }
}
