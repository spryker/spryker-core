<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Persistence;

use Generated\Shared\Transfer\StateMachineItemTransfer;

interface MerchantOmsRepositoryInterface
{
    /**
     * @phpstan-param mixed[] $stateIds
     *
     * @param array $stateIds
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[]
     */
    public function getStateMachineItemsByStateIds(array $stateIds): array;

    /**
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer|null
     */
    public function findCurrentStateByIdSalesOrderItem(int $idSalesOrderItem): ?StateMachineItemTransfer;

    /**
     * @param int[] $merchantOrderItemIds
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[]
     */
    public function findStateHistoryByMerchantOrderIds(array $merchantOrderItemIds): array;
}
