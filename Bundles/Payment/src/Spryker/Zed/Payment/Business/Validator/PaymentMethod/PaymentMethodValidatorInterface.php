<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\Validator\PaymentMethod;

use Generated\Shared\Transfer\PaymentMethodCollectionResponseTransfer;

interface PaymentMethodValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentMethodCollectionResponseTransfer $paymentMethodCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodCollectionResponseTransfer
     */
    public function validate(PaymentMethodCollectionResponseTransfer $paymentMethodCollectionResponseTransfer): PaymentMethodCollectionResponseTransfer;
}
