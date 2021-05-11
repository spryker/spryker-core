<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Business\Reader;

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
     * @phpstan-return array<int|string, array<int, \Generated\Shared\Transfer\StateMachineItemTransfer>>
     *
     * @param int[] $merchantOrderItemIds
     *
     * @return array
     */
    public function getMerchantOrderItemsStateHistory(array $merchantOrderItemIds): array
    {
        $stateMachineItemTransfers = $this->merchantOmsRepository
            ->findStateHistoryByMerchantOrderIds($merchantOrderItemIds);

        $stateMachineItemTransfersGroupedByIdMerchantOrderItem = [];

        foreach ($stateMachineItemTransfers as $stateMachineItemTransfer) {
            /** @var int $identifier */
            $identifier = $stateMachineItemTransfer->getIdentifier();

            $stateMachineItemTransfersGroupedByIdMerchantOrderItem[$identifier][] = $stateMachineItemTransfer;
        }

        return $stateMachineItemTransfersGroupedByIdMerchantOrderItem;
    }
}
