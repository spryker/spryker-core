<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Business\StateMachineItem;

use Generated\Shared\Transfer\MerchantOrderItemCriteriaTransfer;

interface StateMachineItemReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantOrderItemCriteriaTransfer $merchantOrderItemCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[]
     */
    public function getStateMachineItemsByCriteria(MerchantOrderItemCriteriaTransfer $merchantOrderItemCriteriaTransfer): array;
}
