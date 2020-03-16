<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Business\StateMachineItem;

use Generated\Shared\Transfer\StateMachineItemTransfer;
use Spryker\Zed\MerchantOms\Persistence\MerchantOmsRepositoryInterface;

class StateMachineItemReader implements StateMachineItemReaderInterface
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
     * @param int[] $stateIds
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[]
     */
    public function getStateMachineItemsByStateIds(array $stateIds): array
    {
        $stateMachineItemTransfers = [];

        foreach (array_unique($stateIds) as $idState) {
            $merchantOrderItemIds = $this->merchantOmsRepository->getMerchantOrderItemIdsByIdState($idState);

            foreach ($merchantOrderItemIds as $idMerchantOrderItem) {
                $stateMachineItemTransfers[] = (new StateMachineItemTransfer())
                    ->setIdentifier($idMerchantOrderItem)
                    ->setIdItemState($idState);
            }
        }

        return $stateMachineItemTransfers;
    }
}
