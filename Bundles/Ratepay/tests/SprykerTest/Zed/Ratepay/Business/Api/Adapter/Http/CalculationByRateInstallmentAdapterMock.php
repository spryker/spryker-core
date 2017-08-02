<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Ratepay\Business\Api\Adapter\Http;

class CalculationByRateInstallmentAdapterMock extends AbstractAdapterMock
{

    /**
     * @return string
     */
    public function getSuccessResponse()
    {
        return '<?xml version="1.0" encoding="UTF-8"?>
                <response xmlns="urn://www.ratepay.com/payment/1_0" version="1.0">
                <head>
                    <system-id>MyTestsystem</system-id>
                    <operation subtype="calculation-by-rate">CALCULATION_REQUEST</operation>
                    <response-type>INSTALLMENT_PLAN</response-type>
                    <external />
                    <processing>
                        <timestamp>2016-01-06T16:10:50.000</timestamp>
                        <status code="OK">Successfully</status>
                        <reason code="697">Calculation reason: NOT_ALLOWED_RUNTIME: Rate or runtime did not match allowed runtime – rate has been adjusted.</reason>
                        <result code="502">Calculation successful</result>
                    </processing>
                </head>
                <content>
                    <installment-calculation-result>
                        <total-amount>244.67</total-amount>
                        <amount>230</amount>
                        <interest-amount>10.72</interest-amount>
                        <service-charge>3.95</service-charge>
                        <interest-rate>13.7</interest-rate>
                        <annual-percentage-rate>19.36</annual-percentage-rate>
                        <monthly-debit-interest>1.08</monthly-debit-interest>
                        <number-of-rates>6</number-of-rates>
                        <rate>40.79</rate>
                        <last-rate>40.72</last-rate>
                        <payment-firstday>28</payment-firstday>
                    </installment-calculation-result>
                </content>
            </response>';
    }

    /**
     * @return string
     */
    public function getFailureResponse()
    {
        return '<response xmlns="urn://www.ratepay.com/payment/1_0" version="1.0">
            <head>
                <system-id>Spryker www.spryker.dev</system-id>
                <transaction-id>33-201604182754910</transaction-id>
                <transaction-short-id>6IOY.OBTA.W6A8.YXDM</transaction-short-id>
                <operation>CALCULATION_REQUEST</operation>
                <response-type>STATUS_ERROR</response-type>
                <external />
                <processing>
                    <timestamp>2016-04-18T16:48:16.000</timestamp>
                    <status code="ERROR">Error</status>
                    <reason code="213">Validation failed: CALCULATION_REQUEST content must contain payment method.</reason>
                    <result code="150">Processing failed</result>
                </processing>
            </head>
        </response>';
    }

}
