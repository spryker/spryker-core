<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace SprykerTest\Zed\Ratepay\Business\Api\Adapter\Http;

class RequestPaymentInvoiceAdapterMock extends AbstractAdapterMock
{

    /**
     * @return array
     */
    public function getSuccessResponse()
    {
        return '<response xmlns="urn://www.ratepay.com/payment/1_0" version="1.0">
            <head>
                <system-id>Spryker www.spryker.dev</system-id>
                <transaction-id>79-201604182754830</transaction-id>
                <transaction-short-id>UT97.SY3N.XLEC.H4TK</transaction-short-id>
                <operation>PAYMENT_REQUEST</operation>
                <response-type>PAYMENT_PERMISSION</response-type>
                <external>
                    <merchant-consumer-id>2</merchant-consumer-id>
                </external>
                <processing>
                    <timestamp>2016-04-18T16:32:18.000</timestamp>
                    <status code="OK">Successfully</status>
                    <reason code="700">Request successful</reason>
                    <result code="402">Transaction result pending</result>
                    <customer-message>Die Prüfung war erfolgreich. Vielen Dank, dass Sie die Zahlart Rechnung gewählt haben.</customer-message>
                </processing>
            </head>
            <content>
                <customer>
                    <addresses>
                        <address>
                            <street>test street</street>
                            <street-number>1</street-number>
                            <zip-code>12345</zip-code>
                            <city>Berlin</city>
                            <country-code>DE</country-code>
                        </address>
                    </addresses>
                </customer>
                <payment method="INVOICE" currency="EUR">
                    <amount>28.5</amount>
                    <descriptor>DG0496020Y0</descriptor>
                </payment>
            </content>
        </response>';
    }

    /**
     * @return array
     */
    public function getFailureResponse()
    {
        return '<response xmlns="urn://www.ratepay.com/payment/1_0" version="1.0">
            <head>
                <system-id>Spryker www.spryker.dev</system-id>
                <transaction-id>33-201604182754910</transaction-id>
                <transaction-short-id>6IOY.OBTA.W6A8.YXDM</transaction-short-id>
                <operation>PAYMENT_REQUEST</operation>
                <response-type>STATUS_ERROR</response-type>
                <external />
                <processing>
                    <timestamp>2016-04-18T16:48:16.000</timestamp>
                    <status code="ERROR">Error</status>
                    <reason code="213">Validation failed: PAYMENT_REQUEST content must contain payment method.</reason>
                    <result code="150">Processing failed</result>
                </processing>
            </head>
        </response>';
    }

}
