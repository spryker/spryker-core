<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Form\Handler;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ReturnResponseTransfer;

interface ReturnHandlerInterface
{
    /**
     * @phpstan-param array<string, mixed> $returnCreateFormData
     *
     * @param array $returnCreateFormData
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnResponseTransfer
     */
    public function createReturn(array $returnCreateFormData, OrderTransfer $orderTransfer): ReturnResponseTransfer;
}
