<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Model\Response;

class ConfigurationResponse extends BaseResponse
{
    /**
     * @return float
     */
    public function getInterestrateMin()
    {
        return (float)($this->xmlObject->content->{'installment-configuration-result'}->{'interestrate-min'});
    }

    /**
     * @return float
     */
    public function getInterestrateDefault()
    {
        return (float)($this->xmlObject->content->{'installment-configuration-result'}->{'interestrate-default'});
    }

    /**
     * @return float
     */
    public function getInterestrateMax()
    {
        return (float)($this->xmlObject->content->{'installment-configuration-result'}->{'interestrate-max'});
    }

    /**
     * @return float
     */
    public function getInterestRateMerchantTowardsBank()
    {
        return (float)($this->xmlObject->content->{'installment-configuration-result'}->{'interest-rate-merchant-towards-bank'});
    }

    /**
     * @return int
     */
    public function getMonthNumberMin()
    {
        return (int)$this->xmlObject->content->{'installment-configuration-result'}->{'month-number-min'};
    }

    /**
     * @return int
     */
    public function getMonthNumberMax()
    {
        return (int)$this->xmlObject->content->{'installment-configuration-result'}->{'month-number-max'};
    }

    /**
     * @return int
     */
    public function getMonthLongrun()
    {
        return (int)$this->xmlObject->content->{'installment-configuration-result'}->{'month-longrun'};
    }

    /**
     * @return int
     */
    public function getAmountMinLongrun()
    {
        return (int)$this->xmlObject->content->{'installment-configuration-result'}->{'amount-min-longrun'};
    }

    /**
     * @return array
     */
    public function getMonthAllowed()
    {
        $monthAllowed = (string)$this->xmlObject->content->{'installment-configuration-result'}->{'month-allowed'};
        $monthAllowed = array_values(explode(',', $monthAllowed));

        return array_combine($monthAllowed, $monthAllowed);
    }

    /**
     * @return int
     */
    public function getValidPaymentFirstdays()
    {
        return (int)$this->xmlObject->content->{'installment-configuration-result'}->{'valid-payment-firstdays'};
    }

    /**
     * @return int
     */
    public function getPaymentFirstday()
    {
        return (int)$this->xmlObject->content->{'installment-configuration-result'}->{'payment-firstday'};
    }

    /**
     * @return int
     */
    public function getPaymentAmount()
    {
        return (int)$this->xmlObject->content->{'installment-configuration-result'}->{'payment-amount'};
    }

    /**
     * @return int
     */
    public function getPaymentLastrate()
    {
        return (int)$this->xmlObject->content->{'installment-configuration-result'}->{'payment-lastrate'};
    }

    /**
     * @return int
     */
    public function getRateMinNormal()
    {
        return (int)$this->xmlObject->content->{'installment-configuration-result'}->{'rate-min-normal'};
    }

    /**
     * @return int
     */
    public function getRateMinLongrun()
    {
        return (int)$this->xmlObject->content->{'installment-configuration-result'}->{'rate-min-longrun'};
    }

    /**
     * @return float
     */
    public function getServiceCharge()
    {
        return (float)($this->xmlObject->content->{'installment-configuration-result'}->{'service-charge'});
    }

    /**
     * @return int
     */
    public function getMinDifferenceDueday()
    {
        return (int)$this->xmlObject->content->{'installment-configuration-result'}->{'min-difference-dueday'};
    }
}
