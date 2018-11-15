<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Builder;

class InstallmentDetail extends AbstractBuilder implements BuilderInterface
{
    public const ROOT_TAG = 'installment-details';

    /**
     * @return array
     */
    public function buildData()
    {
        $return = [
            'installment-number' => $this->requestTransfer->getInstallmentDetails()->getRatesNumber(),
            'installment-amount' => $this->requestTransfer->getInstallmentDetails()->getAmount(),
            'last-installment-amount' => $this->requestTransfer->getInstallmentDetails()->getLastAmount(),
            'interest-rate' => $this->requestTransfer->getInstallmentDetails()->getInterestRate(),
            'payment-firstday' => $this->requestTransfer->getInstallmentDetails()->getPaymentFirstday(),
        ];

        return $return;
    }

    /**
     * @return string
     */
    public function getRootTag()
    {
        return static::ROOT_TAG;
    }
}
