<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\PickingListCollectionTransfer;
use Generated\Shared\Transfer\PickingListItemTransfer;
use Generated\Shared\Transfer\PickingListTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Orm\Zed\PickingList\Persistence\SpyPickingList;
use Orm\Zed\PickingList\Persistence\SpyPickingListItem;
use Propel\Runtime\Collection\ObjectCollection;

class PickingListMapper
{
    /**
     * @var \Spryker\Zed\PickingList\Persistence\Propel\Mapper\PickingListItemMapper
     */
    protected PickingListItemMapper $pickingListItemMapper;

    /**
     * @var \Spryker\Zed\PickingList\Persistence\Propel\Mapper\WarehouseMapper
     */
    protected WarehouseMapper $warehouseMapper;

    /**
     * @var \Spryker\Zed\PickingList\Persistence\Propel\Mapper\UserMapper
     */
    protected UserMapper $userMapper;

    /**
     * @param \Spryker\Zed\PickingList\Persistence\Propel\Mapper\PickingListItemMapper $pickingListItemMapper
     * @param \Spryker\Zed\PickingList\Persistence\Propel\Mapper\WarehouseMapper $warehouseMapper
     * @param \Spryker\Zed\PickingList\Persistence\Propel\Mapper\UserMapper $userMapper
     */
    public function __construct(
        PickingListItemMapper $pickingListItemMapper,
        WarehouseMapper $warehouseMapper,
        UserMapper $userMapper
    ) {
        $this->pickingListItemMapper = $pickingListItemMapper;
        $this->warehouseMapper = $warehouseMapper;
        $this->userMapper = $userMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     * @param \Orm\Zed\PickingList\Persistence\SpyPickingList $pickingListEntity
     *
     * @return \Orm\Zed\PickingList\Persistence\SpyPickingList
     */
    public function mapPickingListTransferToPickingListEntity(
        PickingListTransfer $pickingListTransfer,
        SpyPickingList $pickingListEntity
    ): SpyPickingList {
        $pickingListEntity = $pickingListEntity
            ->fromArray($pickingListTransfer->modifiedToArray());

        $pickingListEntity = $this->warehouseMapper->mapWarehouseToPickingListEntity($pickingListTransfer, $pickingListEntity);
        $pickingListEntity = $this->userMapper->mapPickingListUserToPickingListEntity($pickingListTransfer, $pickingListEntity);

        return $pickingListEntity;
    }

    /**
     * @param \Orm\Zed\PickingList\Persistence\SpyPickingList $pickingListEntity
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListTransfer
     */
    public function mapPickingListEntityToPickingListTransfer(
        SpyPickingList $pickingListEntity,
        PickingListTransfer $pickingListTransfer
    ): PickingListTransfer {
        $pickingListTransfer = $pickingListTransfer->fromArray(
            $pickingListEntity->toArray(),
            true,
        );

        $pickingListTransfer = $this->userMapper->mapPickingListEntityUserToPickingListTransfer($pickingListEntity, $pickingListTransfer);

        $warehouseTransfer = $this->warehouseMapper->mapWarehouseEntityToWarehouseTransfer(
            $pickingListEntity->getSpyStock(),
            new StockTransfer(),
        );
        $pickingListTransfer->setWarehouse($warehouseTransfer);

        return $pickingListTransfer;
    }

    /**
     * @param \Orm\Zed\PickingList\Persistence\SpyPickingListItem $pickingListItemEntity
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListTransfer
     */
    public function mapPickingListItemEntityToPickingListTransfer(
        SpyPickingListItem $pickingListItemEntity,
        PickingListTransfer $pickingListTransfer
    ): PickingListTransfer {
        return $pickingListTransfer->addPickingListItem(
            $this->pickingListItemMapper->mapPickingListItemEntityToPickingListItemTransfer(
                $pickingListItemEntity,
                new PickingListItemTransfer(),
            ),
        );
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\PickingList\Persistence\SpyPickingList> $pickingListEntityCollection
     * @param \Generated\Shared\Transfer\PickingListCollectionTransfer $pickingListCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionTransfer
     */
    public function mapPickingListEntityCollectionToPickingListCollectionTransfer(
        ObjectCollection $pickingListEntityCollection,
        PickingListCollectionTransfer $pickingListCollectionTransfer
    ): PickingListCollectionTransfer {
        foreach ($pickingListEntityCollection as $pickingListEntity) {
            $pickingListTransfer = $this->mapPickingListEntityToPickingListTransfer(
                $pickingListEntity,
                new PickingListTransfer(),
            );

            foreach ($pickingListEntity->getSpyPickingListItems() as $pickingListItemEntity) {
                $pickingListItemTransfer = $this->pickingListItemMapper
                    ->mapPickingListItemEntityToPickingListItemTransfer(
                        $pickingListItemEntity,
                        new PickingListItemTransfer(),
                    );

                $pickingListTransfer->addPickingListItem($pickingListItemTransfer);
            }

            $pickingListCollectionTransfer->addPickingList($pickingListTransfer);
        }

        return $pickingListCollectionTransfer;
    }
}
