<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Persistence;

use Generated\Shared\Transfer\TransferResponseTransfer;

interface SalesPaymentMerchantEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\TransferResponseTransfer $transferResponseTransfer
     *
     * @return void
     */
    public function saveSalesPaymentMerchantPayout(TransferResponseTransfer $transferResponseTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\TransferResponseTransfer $transferResponseTransfer
     *
     * @return void
     */
    public function saveSalesPaymentMerchantPayoutReversal(TransferResponseTransfer $transferResponseTransfer): void;
}
