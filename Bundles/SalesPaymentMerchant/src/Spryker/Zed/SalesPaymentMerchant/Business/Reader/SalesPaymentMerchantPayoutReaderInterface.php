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
     * @param list<string> $merchantReferences
     *
     * @return \Generated\Shared\Transfer\SalesPaymentMerchantPayoutCollectionTransfer
     */
    public function getSalesPaymentMerchantPayoutCollectionByOrderReferenceAndMerchants(
        string $orderReference,
        array $merchantReferences
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
     * @return array<string, string>
     */
    public function getSalesPaymentMerchantPayoutTransferTransferIdMapIndexedByItemReference(
        SalesPaymentMerchantPayoutCollectionTransfer $salesPaymentMerchantPayoutCollectionTransfer
    ): array;

    /**
     * @param string $orderReference
     * @param list<string> $itemReferences
     *
     * @return array<string, \Generated\Shared\Transfer\SalesPaymentMerchantPayoutTransfer>
     */
    public function getSalesPaymentMerchantPayoutMapByItemReferences(
        string $orderReference,
        array $itemReferences
    ): array;
}
