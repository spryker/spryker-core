<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PickingListItemCollectionTransfer;
use Generated\Shared\Transfer\PickingListItemTransfer;
use Orm\Zed\PickingList\Persistence\SpyPickingListItem;
use Propel\Runtime\Collection\ObjectCollection;

class PickingListItemMapper
{
    /**
     * @param \Generated\Shared\Transfer\PickingListItemTransfer $pickingListItemTransfer
     * @param \Orm\Zed\PickingList\Persistence\SpyPickingListItem $pickingListItemEntity
     *
     * @return \Orm\Zed\PickingList\Persistence\SpyPickingListItem
     */
    public function mapPickingListItemTransferToPickingListItemEntity(
        PickingListItemTransfer $pickingListItemTransfer,
        SpyPickingListItem $pickingListItemEntity
    ): SpyPickingListItem {
        $pickingListItemEntity = $pickingListItemEntity
            ->fromArray($pickingListItemTransfer->modifiedToArray());

        $itemTransfer = $pickingListItemTransfer->getOrderItem();
        if ($itemTransfer !== null && $itemTransfer->getUuid() !== null) {
            $pickingListItemEntity->setSalesOrderItemUuid(
                $itemTransfer->getUuidOrFail(),
            );
        }

        return $pickingListItemEntity;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\PickingList\Persistence\SpyPickingListItem> $pickingListItemEntityCollection
     * @param \Generated\Shared\Transfer\PickingListItemCollectionTransfer $pickingListItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListItemCollectionTransfer
     */
    public function mapPickingListItemEntityCollectionToPickingListItemCollectionTransfer(
        ObjectCollection $pickingListItemEntityCollection,
        PickingListItemCollectionTransfer $pickingListItemCollectionTransfer
    ): PickingListItemCollectionTransfer {
        foreach ($pickingListItemEntityCollection as $pickingListItemEntity) {
            $pickingListItemTransfer = $this->mapPickingListItemEntityToPickingListItemTransfer(
                $pickingListItemEntity,
                new PickingListItemTransfer(),
            );

            $pickingListItemCollectionTransfer->addPickingListItem($pickingListItemTransfer);
        }

        return $pickingListItemCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\PickingList\Persistence\SpyPickingListItem $pickingListItemEntity
     * @param \Generated\Shared\Transfer\PickingListItemTransfer $pickingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListItemTransfer
     */
    public function mapPickingListItemEntityToPickingListItemTransfer(
        SpyPickingListItem $pickingListItemEntity,
        PickingListItemTransfer $pickingListItemTransfer
    ): PickingListItemTransfer {
        return $pickingListItemTransfer->fromArray($pickingListItemEntity->toArray(), true)
            ->setIdPickingList($pickingListItemEntity->getFkPickingList())
            ->setOrderItem(
                (new ItemTransfer())->setUuid($pickingListItemEntity->getSalesOrderItemUuid()),
            );
    }
}
