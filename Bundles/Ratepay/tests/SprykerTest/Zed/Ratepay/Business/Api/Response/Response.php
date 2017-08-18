<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Ratepay\Business\Api\Response;

class Response
{

    /**
     * @return string
     */
    public static function getTestPaymentConfirmResponseData()
    {
        return
            '<response xmlns="urn://www.ratepay.com/payment/1_0" version="1.0">
                <head>
                    <system-id>Spryker www.spryker.dev</system-id>
                    <transaction-id>58-201604122719694</transaction-id>
                    <transaction-short-id>5QTZ.2VWD.OMWW.9D3E</transaction-short-id>
                    <operation>PAYMENT_CONFIRM</operation>
                    <response-type>PAYMENT_PERMISSION</response-type>
                    <external />
                    <processing>
                        <timestamp>2016-04-12T16:27:33.000</timestamp>
                        <status code="OK">Successfully</status>
                        <reason code="303">No RMS reason code</reason>
                        <result code="400">Transaction result successful</result>
                        <customer-message>Die Prüfung war erfolgreich. Vielen Dank, dass Sie die Zahlart Rechnung gewählt haben.</customer-message>
                    </processing>
                </head>
                <content />
            </response>';
    }

    /**
     * @return string
     */
    public static function getTestConfigurationResponseData()
    {
        return
            '<?xml version="1.0" encoding="UTF-8"?>
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
    public static function getTestCalculationResponseData()
    {
        return
            '<?xml version="1.0" encoding="UTF-8"?>
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
    public static function getTestPaymentConfirmUnsuccessResponseData()
    {
        return
            '<response xmlns="urn://www.ratepay.com/payment/1_0" version="1.0">
                <head>
                    <system-id>Spryker www.spryker.dev</system-id>
                    <transaction-id>58-201604122719694</transaction-id>
                    <transaction-short-id>5QTZ.2VWD.OMWW.9D3E</transaction-short-id>
                    <operation>PAYMENT_CONFIRM</operation>
                    <response-type>PAYMENT_PERMISSION</response-type>
                    <external />
                    <processing>
                        <timestamp>2016-04-12T16:27:33.000</timestamp>
                        <status code="OK">Successfully</status>
                        <reason code="303">XXXX</reason>
                        <result code="401">XXXX</result>
                        <customer-message>XXXXXXXX</customer-message>
                    </processing>
                </head>
                <content />
            </response>';
    }

}
