<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Builder;

class InstallmentCalculation extends AbstractBuilder implements BuilderInterface
{
    public const ROOT_TAG = 'installment-calculation';

    public const SUBTYPE_RATE = 'calculation-by-rate';
    public const SUBTYPE_TIME = 'calculation-by-time';

    /**
     * @return array
     */
    public function buildData()
    {
        $return = [
            'amount' => $this->requestTransfer->getInstallmentCalculation()->getAmount(),
        ];
        if ($this->requestTransfer->getInstallmentCalculation()->getPaymentFirstday() !== null) {
            $return['payment-firstday'] = $this->requestTransfer->getInstallmentCalculation()->getPaymentFirstday();
        }
        if ($this->requestTransfer->getInstallmentCalculation()->getCalculationStart() !== null) {
            $return['calculation-start'] = $this->requestTransfer->getInstallmentCalculation()->getCalculationStart();
        }

        if ($this->requestTransfer->getInstallmentCalculation()->getSubType() == self::SUBTYPE_RATE) {
            $return['calculation-rate'] = [
                'rate' => $this->requestTransfer->getInstallmentCalculation()->getCalculationRate(),
            ];
        }
        if ($this->requestTransfer->getInstallmentCalculation()->getSubType() == self::SUBTYPE_TIME) {
            $return['calculation-time'] = [
                'month' => $this->requestTransfer->getInstallmentCalculation()->getMonth(),
            ];
        }

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
