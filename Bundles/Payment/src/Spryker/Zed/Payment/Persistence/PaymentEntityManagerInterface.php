<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Persistence;

use Generated\Shared\Transfer\PaymentMethodTransfer;

interface PaymentEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer|null
     */
    public function updatePaymentMethod(
        PaymentMethodTransfer $paymentMethodTransfer
    ): ?PaymentMethodTransfer;

    /**
     * @param int[] $idStores
     * @param int $idPaymentMethod
     *
     * @return void
     */
    public function addPaymentMethodStoreRelationsForStores(
        array $idStores,
        int $idPaymentMethod
    ): void;

    /**
     * @param int[] $idStores
     * @param int $idPaymentMethod
     *
     * @return void
     */
    public function removePaymentMethodStoreRelationsForStores(
        array $idStores,
        int $idPaymentMethod
    ): void;
}
