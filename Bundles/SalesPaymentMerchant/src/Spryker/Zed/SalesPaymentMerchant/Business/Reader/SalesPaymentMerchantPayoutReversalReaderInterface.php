<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Business\Reader;

use Generated\Shared\Transfer\SalesPaymentMerchantPayoutReversalCollectionTransfer;

interface SalesPaymentMerchantPayoutReversalReaderInterface
{
    /**
     * @param string $orderReference
     * @param string $merchantReference
     * @param bool $isSuccessful
     *
     * @return \Generated\Shared\Transfer\SalesPaymentMerchantPayoutReversalCollectionTransfer
     */
    public function getSalesPaymentMerchantPayoutReversalCollectionByMerchantAndOrderReference(
        string $orderReference,
        string $merchantReference,
        bool $isSuccessful
    ): SalesPaymentMerchantPayoutReversalCollectionTransfer;
}
