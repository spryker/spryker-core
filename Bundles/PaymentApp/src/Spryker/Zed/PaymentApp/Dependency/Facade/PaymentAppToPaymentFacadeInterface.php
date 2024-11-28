<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentApp\Dependency\Facade;

use Generated\Shared\Transfer\PaymentMethodCollectionTransfer;
use Generated\Shared\Transfer\PaymentMethodCriteriaTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface PaymentAppToPaymentFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    public function expandPaymentWithPaymentSelection(
        PaymentTransfer $paymentTransfer,
        StoreTransfer $storeTransfer
    ): PaymentTransfer;

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodCriteriaTransfer $paymentMethodCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodCollectionTransfer
     */
    public function getPaymentMethodCollection(PaymentMethodCriteriaTransfer $paymentMethodCriteriaTransfer): PaymentMethodCollectionTransfer;
}
