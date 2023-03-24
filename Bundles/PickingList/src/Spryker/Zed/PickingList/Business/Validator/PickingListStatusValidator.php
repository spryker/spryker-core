<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PickingListCriteriaTransfer;
use Spryker\Shared\PickingList\PickingListConfig as PickingListSharedConfig;
use Spryker\Zed\PickingList\Business\Mapper\PickingListMapperInterface;
use Spryker\Zed\PickingList\Persistence\PickingListRepositoryInterface;
use Spryker\Zed\PickingList\PickingListConfig;

class PickingListStatusValidator implements PickingListStatusValidatorInterface
{
    /**
     * @var \Spryker\Zed\PickingList\Persistence\PickingListRepositoryInterface
     */
    protected PickingListRepositoryInterface $pickingListRepository;

    /**
     * @var \Spryker\Zed\PickingList\Business\Mapper\PickingListMapperInterface
     */
    protected PickingListMapperInterface $pickingListMapper;

    /**
     * @var \Spryker\Zed\PickingList\PickingListConfig
     */
    protected PickingListConfig $pickingListConfig;

    /**
     * @param \Spryker\Zed\PickingList\Persistence\PickingListRepositoryInterface $pickingListRepository
     * @param \Spryker\Zed\PickingList\Business\Mapper\PickingListMapperInterface $pickingListMapper
     * @param \Spryker\Zed\PickingList\PickingListConfig $pickingListConfig
     */
    public function __construct(
        PickingListRepositoryInterface $pickingListRepository,
        PickingListMapperInterface $pickingListMapper,
        PickingListConfig $pickingListConfig
    ) {
        $this->pickingListRepository = $pickingListRepository;
        $this->pickingListMapper = $pickingListMapper;
        $this->pickingListConfig = $pickingListConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPickingListGenerationFinishedForOrder(OrderTransfer $orderTransfer): bool
    {
        $pickingListTransferCollection = $this->getPickingListTransferCollection($orderTransfer);
        $pickingListItemCount = 0;

        foreach ($pickingListTransferCollection as $pickingListTransfer) {
            $pickingListItemCount += count($pickingListTransfer->getPickingListItems());
        }

        return $pickingListItemCount === count($orderTransfer->getItems());
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPickingStartedForOrder(OrderTransfer $orderTransfer): bool
    {
        $pickingListTransferCollection = $this->getPickingListTransferCollection($orderTransfer);

        $orderPickingListStartedStatuses = $this->pickingListConfig->getOrderPickingListStartedStatuses();
        foreach ($pickingListTransferCollection as $pickingListTransfer) {
            if (in_array($pickingListTransfer->getStatus(), $orderPickingListStartedStatuses, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPickingFinishedForOrder(OrderTransfer $orderTransfer): bool
    {
        $pickingListTransferCollection = $this->getPickingListTransferCollection($orderTransfer);

        $totalPickingListsCount = $pickingListTransferCollection->count();
        if ($totalPickingListsCount === 0) {
            return false;
        }

        $finishedPickingListCount = 0;
        foreach ($pickingListTransferCollection as $pickingListTransfer) {
            if ($pickingListTransfer->getStatus() === PickingListSharedConfig::STATUS_PICKING_FINISHED) {
                $finishedPickingListCount++;
            }
        }

        return $totalPickingListsCount == $finishedPickingListCount;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PickingListTransfer>
     */
    protected function getPickingListTransferCollection(OrderTransfer $orderTransfer): ArrayObject
    {
        $pickingListCriteriaTransfer = $this->pickingListMapper
            ->mapOrderTransferToPickingListCriteriaTransfer(
                $orderTransfer,
                new PickingListCriteriaTransfer(),
            );

        $pickingListCollectionTransfer = $this->pickingListRepository
            ->getPickingListCollection($pickingListCriteriaTransfer);

        /** @var \ArrayObject<int, \Generated\Shared\Transfer\PickingListTransfer> $pickingListTransferCollection */
        $pickingListTransferCollection = $pickingListCollectionTransfer->getPickingLists();

        return $pickingListTransferCollection;
    }
}
