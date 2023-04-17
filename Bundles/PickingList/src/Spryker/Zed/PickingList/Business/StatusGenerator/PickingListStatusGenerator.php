<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Business\StatusGenerator;

use ArrayObject;
use Generated\Shared\Transfer\PickingListItemCollectionTransfer;
use Generated\Shared\Transfer\PickingListItemConditionsTransfer;
use Generated\Shared\Transfer\PickingListItemCriteriaTransfer;
use Generated\Shared\Transfer\PickingListItemTransfer;
use Generated\Shared\Transfer\PickingListTransfer;
use Spryker\Shared\PickingList\PickingListConfig;
use Spryker\Zed\PickingList\Persistence\PickingListRepositoryInterface;

class PickingListStatusGenerator implements PickingListStatusGeneratorInterface
{
    /**
     * @var \Spryker\Zed\PickingList\Persistence\PickingListRepositoryInterface
     */
    protected PickingListRepositoryInterface $pickingListRepository;

    /**
     * @param \Spryker\Zed\PickingList\Persistence\PickingListRepositoryInterface $pickingListRepository
     */
    public function __construct(
        PickingListRepositoryInterface $pickingListRepository
    ) {
        $this->pickingListRepository = $pickingListRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     *
     * @return string
     */
    public function generatePickingListStatus(PickingListTransfer $pickingListTransfer): string
    {
        if ($this->isPickingFinished($pickingListTransfer)) {
            return PickingListConfig::STATUS_PICKING_FINISHED;
        }

        if (!$pickingListTransfer->getUser()) {
            return PickingListConfig::STATUS_READY_FOR_PICKING;
        }

        return PickingListConfig::STATUS_PICKING_STARTED;
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     *
     * @return bool
     */
    protected function isPickingFinished(PickingListTransfer $pickingListTransfer): bool
    {
        /** @var \ArrayObject<int, \Generated\Shared\Transfer\PickingListItemTransfer> $pickingListItemTransferCollection */
        $pickingListItemTransferCollection = $pickingListTransfer->getPickingListItems();

        /** @var \ArrayObject<int, \Generated\Shared\Transfer\PickingListItemTransfer> $existingPickingListItemTransferCollection */
        $existingPickingListItemTransferCollection = $this->getExistingPickingListItemCollectionTransfer($pickingListTransfer)
            ->getPickingListItems();

        $pickingListItemTransferCollection = $this->mergePickingListItemCollections(
            $pickingListItemTransferCollection,
            $existingPickingListItemTransferCollection,
        );

        return $this->isPickingListItemTransferCollectionPicked(
            $pickingListItemTransferCollection,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListItemCollectionTransfer
     */
    protected function getExistingPickingListItemCollectionTransfer(
        PickingListTransfer $pickingListTransfer
    ): PickingListItemCollectionTransfer {
        $pickingListId = $pickingListTransfer->getIdPickingList();
        if (!$pickingListId) {
            return new PickingListItemCollectionTransfer();
        }

        return $this->pickingListRepository
            ->getPickingListItemCollection(
                $this->createPickingListItemCriteriaTransfer($pickingListId),
            );
    }

    /**
     * @param int $pickingListId
     *
     * @return \Generated\Shared\Transfer\PickingListItemCriteriaTransfer
     */
    protected function createPickingListItemCriteriaTransfer(int $pickingListId): PickingListItemCriteriaTransfer
    {
        $pickingListItemConditionsTransfer = (new PickingListItemConditionsTransfer())

            ->addPickingListId($pickingListId);

        return (new PickingListItemCriteriaTransfer())
            ->setPickingListItemConditions($pickingListItemConditionsTransfer);
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PickingListItemTransfer> $pickingListItemTransferCollection
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PickingListItemTransfer> $existingPickingListItemTransferCollection
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PickingListItemTransfer>
     */
    protected function mergePickingListItemCollections(
        ArrayObject $pickingListItemTransferCollection,
        ArrayObject $existingPickingListItemTransferCollection
    ): ArrayObject {
        if ($existingPickingListItemTransferCollection->count() === 0) {
            return $pickingListItemTransferCollection;
        }

        $mergedPickingListItemTransferCollection = new ArrayObject(
            $pickingListItemTransferCollection->getArrayCopy(),
        );

        $pickingListItemUuids = $this->getPickingListItemUuids($pickingListItemTransferCollection);
        foreach ($existingPickingListItemTransferCollection as $pickingListItemTransfer) {
            $pickingListItemUuid = $pickingListItemTransfer->getUuidOrFail();
            if (in_array($pickingListItemUuid, $pickingListItemUuids)) {
                continue;
            }

            $mergedPickingListItemTransferCollection->append($pickingListItemTransfer);
        }

        return $mergedPickingListItemTransferCollection;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PickingListItemTransfer> $pickingListItemTransferCollection
     *
     * @return list<string>
     */
    protected function getPickingListItemUuids(ArrayObject $pickingListItemTransferCollection): array
    {
        $pickingListItemUuids = [];
        foreach ($pickingListItemTransferCollection as $pickingListItemTransfer) {
            $pickingListItemUuid = $pickingListItemTransfer->getUuid();
            if (!$pickingListItemUuid) {
                continue;
            }

            $pickingListItemUuids[] = $pickingListItemUuid;
        }

        return $pickingListItemUuids;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PickingListItemTransfer> $pickingListItemTransferCollection
     *
     * @return bool
     */
    protected function isPickingListItemTransferCollectionPicked(
        ArrayObject $pickingListItemTransferCollection
    ): bool {
        if ($pickingListItemTransferCollection->count() === 0) {
            return false;
        }

        foreach ($pickingListItemTransferCollection as $pickingListItemTransfer) {
            if (!$this->isItemPicked($pickingListItemTransfer)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListItemTransfer $pickingListItemTransfer
     *
     * @return bool
     */
    protected function isItemPicked(PickingListItemTransfer $pickingListItemTransfer): bool
    {
        $quantity = $pickingListItemTransfer->getQuantityOrFail();
        $numberOfNotPicked = $pickingListItemTransfer->getNumberOfNotPickedOrFail();
        $numberOfPicked = $pickingListItemTransfer->getNumberOfPickedOrFail();

        return $quantity !== 0 && $quantity === $numberOfPicked && !$numberOfNotPicked;
    }
}
