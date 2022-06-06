<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\PaymentProviderValidator;

use Generated\Shared\Transfer\PaymentProviderCollectionResponseTransfer;

class PaymentProviderValidatorComposite implements PaymentProviderValidatorInterface
{
    /**
     * @var array<\Spryker\Zed\Payment\Business\PaymentProviderValidator\PaymentProviderValidatorInterface>
     */
    protected $paymentProviderValidators;

    /**
     * @param array<\Spryker\Zed\Payment\Business\PaymentProviderValidator\PaymentProviderValidatorInterface> $paymentProviderValidators
     */
    public function __construct(array $paymentProviderValidators)
    {
        $this->paymentProviderValidators = $paymentProviderValidators;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentProviderCollectionResponseTransfer $paymentProviderCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentProviderCollectionResponseTransfer
     */
    public function validate(
        PaymentProviderCollectionResponseTransfer $paymentProviderCollectionResponseTransfer
    ): PaymentProviderCollectionResponseTransfer {
        foreach ($this->paymentProviderValidators as $paymentProviderValidator) {
            $paymentProviderCollectionResponseTransfer = $paymentProviderValidator->validate($paymentProviderCollectionResponseTransfer);
        }

        return $paymentProviderCollectionResponseTransfer;
    }
}
