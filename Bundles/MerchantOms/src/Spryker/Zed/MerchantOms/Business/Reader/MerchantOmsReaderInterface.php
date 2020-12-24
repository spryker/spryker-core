<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Business\Reader;

interface MerchantOmsReaderInterface
{
    /**
     * @phpstan-return array<int, array<\Generated\Shared\Transfer\StateMachineItemTransfer>>
     *
     * @param int[] $merchantOrderItemIds
     *
     * @return array
     */
    public function getMerchantOrderItemsStateHistory(array $merchantOrderItemIds): array;
}
