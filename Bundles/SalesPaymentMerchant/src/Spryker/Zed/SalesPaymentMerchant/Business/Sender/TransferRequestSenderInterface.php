<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Business\Sender;

use Generated\Shared\Transfer\TransferResponseCollectionTransfer;

interface TransferRequestSenderInterface
{
    /**
     * @param array<string, array<int, array<string, mixed>>|int> $transferRequestData
     * @param string $transferEndpoint
     *
     * @return \Generated\Shared\Transfer\TransferResponseCollectionTransfer
     */
    public function requestTransfer(
        array $transferRequestData,
        string $transferEndpoint
    ): TransferResponseCollectionTransfer;
}
