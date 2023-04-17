<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\PickingListItemTransfer;
use Generated\Shared\Transfer\PickingListTransfer;
use Orm\Zed\PickingList\Persistence\SpyPickingList;
use Orm\Zed\PickingList\Persistence\SpyPickingListItem;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\PickingList\Persistence\PickingListPersistenceFactory getFactory()
 */
class PickingListEntityManager extends AbstractEntityManager implements PickingListEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListTransfer
     */
    public function createPickingList(PickingListTransfer $pickingListTransfer): PickingListTransfer
    {
        $persistedPickingListTransfer = $this->savePickingListEntity(
            $pickingListTransfer,
            new SpyPickingList(),
        );

        $persistedPickingListTransfer = $this->createPickingListItems(
            $pickingListTransfer,
            $persistedPickingListTransfer,
        );

        return $persistedPickingListTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListTransfer
     */
    public function updatePickingList(PickingListTransfer $pickingListTransfer): PickingListTransfer
    {
        $persistedPickingListTransfer = $this->updatePickingListEntity($pickingListTransfer);
        if (!$persistedPickingListTransfer) {
            return $pickingListTransfer;
        }

        $persistedPickingListTransfer = $this->updatePickingListItems(
            $pickingListTransfer,
            $persistedPickingListTransfer,
        );

        return $persistedPickingListTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     * @param \Orm\Zed\PickingList\Persistence\SpyPickingList $pickingListEntity
     *
     * @return \Generated\Shared\Transfer\PickingListTransfer
     */
    protected function savePickingListEntity(
        PickingListTransfer $pickingListTransfer,
        SpyPickingList $pickingListEntity
    ): PickingListTransfer {
        $pickingListMapper = $this->getFactory()->createPickingListMapper();

        $pickingListEntity = $pickingListMapper->mapPickingListTransferToPickingListEntity(
            $pickingListTransfer,
            $pickingListEntity,
        );

        $pickingListEntity->save();

        return $pickingListMapper->mapPickingListEntityToPickingListTransfer(
            $pickingListEntity,
            $pickingListTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListTransfer|null
     */
    protected function updatePickingListEntity(PickingListTransfer $pickingListTransfer): ?PickingListTransfer
    {
        $pickingListEntity = $this->getFactory()
            ->createPickingListQuery()
            ->filterByUuid($pickingListTransfer->getUuidOrFail())
            ->findOne();

        if (!$pickingListEntity) {
            return null;
        }

        return $this->savePickingListEntity(
            $pickingListTransfer,
            $pickingListEntity,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     * @param \Generated\Shared\Transfer\PickingListTransfer $persistedPickingListTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListTransfer
     */
    protected function createPickingListItems(
        PickingListTransfer $pickingListTransfer,
        PickingListTransfer $persistedPickingListTransfer
    ): PickingListTransfer {
        $persistedPickingListItems = new ArrayObject();
        foreach ($pickingListTransfer->getPickingListItems() as $pickingListItemTransfer) {
            $persistedPickingListItemTransfer = $this->createPickingListItem(
                $pickingListItemTransfer,
                $persistedPickingListTransfer,
            );

            $persistedPickingListItems->append($persistedPickingListItemTransfer);
        }

        return $persistedPickingListTransfer->setPickingListItems($persistedPickingListItems);
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     * @param \Generated\Shared\Transfer\PickingListTransfer $persistedPickingListTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListTransfer
     */
    protected function updatePickingListItems(
        PickingListTransfer $pickingListTransfer,
        PickingListTransfer $persistedPickingListTransfer
    ): PickingListTransfer {
        /** @var \ArrayObject<int, \Generated\Shared\Transfer\PickingListItemTransfer> $pickingListItemCollection */
        $pickingListItemCollection = $pickingListTransfer->getPickingListItems();
        foreach ($pickingListItemCollection as $pickingListItemTransfer) {
            $this->updatePickingListItem($pickingListItemTransfer);
        }

        return $persistedPickingListTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListItemTransfer $pickingListItemTransfer
     * @param \Generated\Shared\Transfer\PickingListTransfer $persistedPickingListTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListItemTransfer
     */
    protected function createPickingListItem(
        PickingListItemTransfer $pickingListItemTransfer,
        PickingListTransfer $persistedPickingListTransfer
    ): PickingListItemTransfer {
        $pickingListItemMapper = $this->getFactory()
            ->createPickingListItemMapper();

        $pickingListItemEntity = $pickingListItemMapper->mapPickingListItemTransferToPickingListItemEntity(
            $pickingListItemTransfer,
            (new SpyPickingListItem())->setFkPickingList(
                $persistedPickingListTransfer->getIdPickingListOrFail(),
            ),
        );
        $pickingListItemEntity->save();

        return $pickingListItemMapper->mapPickingListItemEntityToPickingListItemTransfer(
            $pickingListItemEntity,
            $pickingListItemTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListItemTransfer $pickingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListItemTransfer|null
     */
    protected function updatePickingListItem(PickingListItemTransfer $pickingListItemTransfer): ?PickingListItemTransfer
    {
        $pickingListItemMapper = $this->getFactory()
            ->createPickingListItemMapper();

        $pickingListItemEntity = $this->getFactory()
            ->createPickingListItemQuery()
            ->filterByUuid($pickingListItemTransfer->getUuidOrFail())
            ->findOne();

        if (!$pickingListItemEntity) {
            return null;
        }

        $pickingListItemEntity = $pickingListItemMapper->mapPickingListItemTransferToPickingListItemEntity(
            $pickingListItemTransfer,
            $pickingListItemEntity,
        );

        $pickingListItemEntity->save();

        return $pickingListItemMapper->mapPickingListItemEntityToPickingListItemTransfer(
            $pickingListItemEntity,
            $pickingListItemTransfer,
        );
    }
}
