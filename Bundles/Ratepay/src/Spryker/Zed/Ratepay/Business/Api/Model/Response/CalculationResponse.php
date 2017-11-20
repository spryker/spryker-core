<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Model\Response;

class CalculationResponse extends BaseResponse
{
    /**
     * @return float
     */
    public function getTotalAmount()
    {
        return (float)($this->xmlObject->content->{'installment-calculation-result'}->{'total-amount'});
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return (float)($this->xmlObject->content->{'installment-calculation-result'}->{'amount'});
    }

    /**
     * @return float
     */
    public function getInterestAmount()
    {
        return (float)($this->xmlObject->content->{'installment-calculation-result'}->{'interest-amount'});
    }

    /**
     * @return float
     */
    public function getServiceCharge()
    {
        return (float)($this->xmlObject->content->{'installment-calculation-result'}->{'service-charge'});
    }

    /**
     * @return float
     */
    public function getInterestRate()
    {
        return (float)($this->xmlObject->content->{'installment-calculation-result'}->{'interest-rate'});
    }

    /**
     * @return float
     */
    public function getAnnualPercentageRate()
    {
        return (float)($this->xmlObject->content->{'installment-calculation-result'}->{'annual-percentage-rate'});
    }

    /**
     * @return float
     */
    public function getMonthlyDebitInterest()
    {
        return (float)($this->xmlObject->content->{'installment-calculation-result'}->{'monthly-debit-interest'});
    }

    /**
     * @return float
     */
    public function getRate()
    {
        return (float)($this->xmlObject->content->{'installment-calculation-result'}->{'rate'});
    }

    /**
     * @return float
     */
    public function getLastRate()
    {
        return (float)($this->xmlObject->content->{'installment-calculation-result'}->{'last-rate'});
    }

    /**
     * @return int
     */
    public function getNumberOfRates()
    {
        return (int)$this->xmlObject->content->{'installment-calculation-result'}->{'number-of-rates'};
    }

    /**
     * @return int
     */
    public function getPaymentFirstday()
    {
        return (int)$this->xmlObject->content->{'installment-calculation-result'}->{'payment-firstday'};
    }
}
