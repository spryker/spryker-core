<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\Validator\PaymentMethod;

use Generated\Shared\Transfer\PaymentMethodCollectionResponseTransfer;

class PaymentMethodValidatorComposite implements PaymentMethodValidatorInterface
{
    /**
     * @var array<\Spryker\Zed\Payment\Business\Validator\PaymentMethod\PaymentMethodValidatorInterface>
     */
    protected $paymentMethodValidators;

    /**
     * @param array<\Spryker\Zed\Payment\Business\Validator\PaymentMethod\PaymentMethodValidatorInterface> $paymentMethodValidators
     */
    public function __construct(array $paymentMethodValidators)
    {
        $this->paymentMethodValidators = $paymentMethodValidators;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodCollectionResponseTransfer $paymentMethodCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodCollectionResponseTransfer
     */
    public function validate(PaymentMethodCollectionResponseTransfer $paymentMethodCollectionResponseTransfer): PaymentMethodCollectionResponseTransfer
    {
        foreach ($this->paymentMethodValidators as $paymentMethodValidator) {
            $paymentMethodCollectionResponseTransfer = $paymentMethodValidator->validate($paymentMethodCollectionResponseTransfer);
        }

        return $paymentMethodCollectionResponseTransfer;
    }
}
