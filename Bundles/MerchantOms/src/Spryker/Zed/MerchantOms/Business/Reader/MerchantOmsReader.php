<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Business\Reader;

use ArrayObject;
use Generated\Shared\Transfer\MerchantOrderItemTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Spryker\Zed\MerchantOms\Persistence\MerchantOmsRepositoryInterface;

class MerchantOmsReader implements MerchantOmsReaderInterface
{
    /**
     * @var \Spryker\Zed\MerchantOms\Persistence\MerchantOmsRepositoryInterface
     */
    protected $merchantOmsRepository;

    /**
     * @param \Spryker\Zed\MerchantOms\Persistence\MerchantOmsRepositoryInterface $merchantOmsRepository
     */
    public function __construct(MerchantOmsRepositoryInterface $merchantOmsRepository)
    {
        $this->merchantOmsRepository = $merchantOmsRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer
     */
    public function expandMerchantOrderItemsWithStateHistory(MerchantOrderTransfer $merchantOrderTransfer): MerchantOrderTransfer
    {
        $stateMachineItemTransfers = $this->merchantOmsRepository->findStateHistoryByMerchantOrderIds(
            array_map(
                function (MerchantOrderItemTransfer $merchantOrderItemTransfer) {
                    return $merchantOrderItemTransfer->getIdMerchantOrderItem();
                },
                $merchantOrderTransfer->getMerchantOrderItems()->getArrayCopy()
            )
        );
        $stateMachineItemTransfersGroupedByIdMerchantOrderItem = [];

        foreach ($stateMachineItemTransfers as $stateMachineItemTransfer) {
            $stateMachineItemTransfersGroupedByIdMerchantOrderItem[$stateMachineItemTransfer->getIdentifier()][] = $stateMachineItemTransfer;
        }

        foreach ($merchantOrderTransfer->getMerchantOrderItems() as $merchantOrderItemTransfer) {
            if (!isset($stateMachineItemTransfersGroupedByIdMerchantOrderItem[$merchantOrderItemTransfer->getIdMerchantOrderItem()])) {
                continue;
            }
            $merchantOrderItemTransfer->setStateHistory(
                new ArrayObject($stateMachineItemTransfersGroupedByIdMerchantOrderItem[$merchantOrderItemTransfer->getIdMerchantOrderItem()])
            );
        }

        return $merchantOrderTransfer;
    }
}
