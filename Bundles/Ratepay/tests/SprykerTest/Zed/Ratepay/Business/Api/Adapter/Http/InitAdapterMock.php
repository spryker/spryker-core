<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace SprykerTest\Zed\Ratepay\Business\Api\Adapter\Http;

class InitAdapterMock extends AbstractAdapterMock
{

    /**
     * @return array
     */
    public function getSuccessResponse()
    {
        return '<response xmlns="urn://www.ratepay.com/payment/1_0" version="1.0">
            <head>
                <system-id>Spryker www.spryker.dev</system-id>
                <transaction-id>90-201604182752854</transaction-id>
                <transaction-short-id>N3YG.6X9Q.2KWA.DVTU</transaction-short-id>
                <operation>PAYMENT_INIT</operation>
                <response-type>STATUS_RESPONSE</response-type>
                <external />
                <processing>
                    <timestamp>2016-04-18T10:56:33.000</timestamp>
                    <status code="OK">Successfully</status>
                    <reason code="300">Processing successful</reason>
                    <result code="350">Transaction initialized</result>
                </processing>
            </head>
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
                <transaction-id>95-201604182753014</transaction-id>
                <transaction-short-id>3EE9.WTHQ.V7SI.NV81</transaction-short-id>
                <operation>PAYMENT_INIT</operation>
                <response-type>STATUS_ERROR</response-type>
                <processing>
                    <timestamp>2016-04-18T11:54:08.000</timestamp>
                    <status code="ERROR">Error</status>
                    <reason code="200">Validation failed</reason>
                    <result code="150">Processing failed</result>
                    <customer-message>Leider ist eine Bezahlung mit der gewählten Zahlungsart Rechnung nicht möglich. Diese Entscheidung ist auf Grundlage einer automatisierten Datenverarbeitung getroffen worden. Einzelheiten finden sie in den &lt;a href="https://www.ratepay.com/zusaetzliche-geschaeftsbedingungen-und-datenschutzhinweis-de" target="_blank"&gt;zusätzlichen Allgemeinen Geschäftsbedingungen und dem Datenschutzhinweis für RatePAY-Zahlungsarten&lt;/a&gt;.</customer-message>
                </processing>
            </head>
        </response>';
    }

}
