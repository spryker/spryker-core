<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace SprykerTest\Zed\Ratepay\Business\Api\Adapter\Http;

class ConfirmPaymentAdapterMock extends AbstractAdapterMock
{

    /**
     * @return array
     */
    public function getSuccessResponse()
    {
        return '<response xmlns="urn://www.ratepay.com/payment/1_0" version="1.0">
            <head>
                <system-id>Spryker www.spryker.dev</system-id>
                <transaction-id>44-201604182752934</transaction-id>
                <transaction-short-id>FYLB.66N1.B5CS.DY2J</transaction-short-id>
                <operation>PAYMENT_CONFIRM</operation>
                <response-type>PAYMENT_PERMISSION</response-type>
                <external>
                    <order-id>DE--76</order-id>
                </external>
                <processing>
                    <timestamp>2016-04-18T11:26:26.000</timestamp>
                    <status code="OK">Successfully</status>
                    <reason code="303">No RMS reason code</reason>
                    <result code="400">Transaction result successful</result>
                </processing>
            </head>
            <content />
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
                <transaction-id>48-201604182753838</transaction-id>
                <transaction-short-id>FZOV.YVGL.NU3E.ZR5Y-234242</transaction-short-id>
                <operation>PAYMENT_CONFIRM</operation>
                <response-type>STATUS_ERROR</response-type>
                <external />
                <processing>
                    <timestamp>2016-04-18T15:00:58.000</timestamp>
                    <status code="ERROR">Error</status>
                    <reason code="200">Validation failed: cvc-pattern-valid: Value \'FZOV.YVGL.NU3E.ZR5Y-234242\' is not facet-valid with respect to pattern for type \'transactionShortIdType\'. (some more validation errors present)</reason>
                    <result code="150">Processing failed</result>
                </processing>
            </head>
        </response>';
    }

}
