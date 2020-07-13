<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Persistence;

interface MerchantOmsRepositoryInterface
{
    /**
     * @param array $stateIds
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[]
     */
    public function getStateMachineItemsByStateIds(array $stateIds): array;

    /**
     * @param array $stateIds
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[]
     */
    public function getStateMachineItemStatesByStateIds(array $stateIds): array;
}
