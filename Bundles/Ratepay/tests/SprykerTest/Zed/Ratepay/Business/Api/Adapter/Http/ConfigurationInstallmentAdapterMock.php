<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Ratepay\Business\Api\Adapter\Http;

class ConfigurationInstallmentAdapterMock extends AbstractAdapterMock
{
    /**
     * @return string
     */
    public function getSuccessResponse()
    {
        return '<?xml version="1.0" encoding="UTF-8"?>
                <response version="1.0" xmlns="urn://www.ratepay.com/payment/1_0">
                    <head>
                        <system-id>MyTestsystem</system-id>
                        <operation>CONFIGURATION_REQUEST</operation>
                        <response-type>CONFIGURATION_SETTINGS</response-type>
                        <external/>
                        <processing>
                            <timestamp>2016-01-11T15:50:09.000</timestamp>
                            <status code="OK">Successfully</status>
                            <reason code="306">Calculation configuration read successful</reason>
                            <result code="500">Calculation configuration processed</result>
                        </processing>
                    </head>
                    <content>
                        <installment-configuration-result name="INTEGRATION_TE_DACH" type="DEFAULT">
                            <interestrate-min>13.7</interestrate-min>
                            <interestrate-default>13.7</interestrate-default>
                            <interestrate-max>13.7</interestrate-max>
                            <interest-rate-merchant-towards-bank>13.7</interest-rate-merchant-towards-bank>
                            <month-number-min>3</month-number-min>
                            <month-number-max>48</month-number-max>
                            <month-longrun>0</month-longrun>
                            <amount-min-longrun>0</amount-min-longrun>
                            <month-allowed>3,6,12,24,36,48</month-allowed>
                            <valid-payment-firstdays>28</valid-payment-firstdays>
                            <payment-firstday>28</payment-firstday>
                            <payment-amount>200</payment-amount>
                            <payment-lastrate>0</payment-lastrate>
                            <rate-min-normal>20</rate-min-normal>
                            <rate-min-longrun>0</rate-min-longrun>
                            <service-charge>3.95</service-charge>
                            <min-difference-dueday>28</min-difference-dueday>
                        </installment-configuration-result>
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
                <operation>CONFIGURATION_REQUEST</operation>
                <response-type>STATUS_ERROR</response-type>
                <external />
                <processing>
                    <timestamp>2016-04-18T16:48:16.000</timestamp>
                    <status code="ERROR">Error</status>
                    <reason code="213">Validation failed: CONFIGURATION_REQUEST content must contain payment method.</reason>
                    <result code="150">Processing failed</result>
                </processing>
            </head>
        </response>';
    }
}
