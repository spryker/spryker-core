<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Business\Reader;

use Generated\Shared\Transfer\SalesPaymentMerchantPayoutCollectionTransfer;

interface SalesPaymentMerchantPayoutReaderInterface
{
    /**
     * @param string $orderReference
     *
     * @return \Generated\Shared\Transfer\SalesPaymentMerchantPayoutCollectionTransfer
     */
    public function getSalesPaymentMerchantPayoutCollectionByOrderReference(
        string $orderReference
    ): SalesPaymentMerchantPayoutCollectionTransfer;

    /**
     * @param string $orderReference
     * @param string $merchantReference
     * @param bool $isSuccessful
     *
     * @return \Generated\Shared\Transfer\SalesPaymentMerchantPayoutCollectionTransfer
     */
    public function getSalesPaymentMerchantPayoutCollectionByMerchantAndOrderReference(
        string $orderReference,
        string $merchantReference,
        bool $isSuccessful
    ): SalesPaymentMerchantPayoutCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\SalesPaymentMerchantPayoutCollectionTransfer $salesPaymentMerchantPayoutCollectionTransfer
     *
     * @return array<string, array<string, string>>
     */
    public function getSalesPaymentMerchantPayoutTransferItemReferencesMapIndexedByTransferId(
        SalesPaymentMerchantPayoutCollectionTransfer $salesPaymentMerchantPayoutCollectionTransfer
    ): array;
}
