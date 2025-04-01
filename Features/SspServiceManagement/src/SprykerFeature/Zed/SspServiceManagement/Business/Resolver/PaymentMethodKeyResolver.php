<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspServiceManagement\Business\Resolver;

use Generated\Shared\Transfer\PaymentMethodConditionsTransfer;
use Generated\Shared\Transfer\PaymentMethodCriteriaTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Spryker\Zed\Payment\Business\PaymentFacadeInterface;

class PaymentMethodKeyResolver implements PaymentMethodKeyResolverInterface
{
 /**
  * @param \Spryker\Zed\Payment\Business\PaymentFacadeInterface $paymentFacade
  */
    public function __construct(protected PaymentFacadeInterface $paymentFacade)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return string
     */
    public function resolvePaymentMethodKey(PaymentTransfer $paymentTransfer): string
    {
        $paymentProviderName = $paymentTransfer->getPaymentProviderOrFail();
        $paymentMethodName = $paymentTransfer->getPaymentMethodOrFail();

        $paymentMethodCriteriaTransfer = (new PaymentMethodCriteriaTransfer())
            ->setPaymentMethodConditions(
                (new PaymentMethodConditionsTransfer())
                    ->addName($paymentMethodName)
                    ->addPaymentProviderKey($paymentProviderName),
            );

        $paymentMethodCollectionTransfer = $this->paymentFacade
            ->getPaymentMethodCollection($paymentMethodCriteriaTransfer);

        if ($paymentMethodCollectionTransfer->getPaymentMethods()->count() === 0) {
            return $this->paymentFacade
                ->generatePaymentMethodKey($paymentProviderName, $paymentMethodName);
        }

        $paymentMethodTransfer = $paymentMethodCollectionTransfer->getPaymentMethods()->offsetGet(0);

        return $paymentMethodTransfer->getPaymentMethodKeyOrFail();
    }
}
